<?php

namespace App\Http\Controllers;

use App\Models\CommonDetails;
use Illuminate\Http\Request;
use App\Models\GymEnquiries;
use App\Models\GymSetting;
use Carbon\Carbon;
use App\Models\MerchantPromotionDatabase;
use App\Models\GymEnquiriesFollowUp;
use Illuminate\Support\Facades\Validator;
class EnquiryController extends Controller
{
    public function checkBranch($branch_slug){
        $branch = CommonDetails::where('slug',$branch_slug)->first();
        if($branch == null){
            abort(404);
        } 
        return $branch;
    }
    public function showEnquiryForm(Request $request){
        $old_url = url()->current();
        $branch_name = explode('/',$old_url);
        $branch = $this->checkBranch($branch_name[4]);
        $logo = GymSetting::where('detail_id',$branch->id)->pluck('image')->first();
        return view('customer-enquiry',compact('branch','logo'));
    }
     public function store(Request $request)
    {
        $validator         = Validator::make($request->all(), [
            'customer_name' => 'required|string',
            'customer_lname' => 'required|string',
            'email'          => 'required|email|unique:gym_enquiries,email',
            'mobile'         => 'required|digits:10|unique:gym_enquiries,mobile',
            'address' => 'required|string',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withErrors($errors)->withInput();
        } else {
            $inputData = $request->all();
            $inputData['detail_id'] = $inputData['branch'];
            $inputData['enquiry_date'] = now()->format('Y-m-d');
            $followUpData = [
                'packages_offered'  => 'Monthly',
                'counselor_name'    => 'Admin',
                'follow_up_date'    => now()->format('Y-m-d'),
                'next_follow_up_on' => now()->addDay()->format('Y-m-d'),
            ];

            $gymEnquiry = GymEnquiries::create($inputData);

            $followUpData['gym_enquiry_id'] = $gymEnquiry->id;

            // Insert follow up details
            GymEnquiriesFollowUp::create($followUpData);

            // Save follow up dates to gym_enquiry table
            $gymEnquiry->previous_follow_up = now()->format('Y-m-d');
            $gymEnquiry->next_follow_up     = now()->addDay()->format('Y-m-d');
            $gymEnquiry->save();

            if(isset($request->email)) {
                $data = [
                    'name'   => $request->customer_name.' '.$request->customer_mname.' '.$request->customer_lname,
                    'email'  => $request->email,
                    'number' => $request->mobile,
                    'age'    => $request->age ?? null,
                    'gender' => $request->sex,
                ];
                $this->addPromotionDatabase($data,$request->get('branch'));
            }
            return redirect()->back()->with('success', 'Enquiry form submitted successfully.');
        }
    }

    public function addPromotionDatabase($data,$branch_id)
    {
        if ($data['number'][0] === "0") {
            $number = substr($data['number'], 1);
        } elseif (substr($data['number'], 0, 3) == "+91") {
            $number = substr($data['number'], 3);
        } else {
            $number = $data['number'];
        }

        $user = MerchantPromotionDatabase::where('mobile',$number)->where('detail_id', $branch_id)->first();

        if (!is_null($user)) {
            $user->name   = $data['name'];
            $user->email  = $data['email'];
            $user->age    = $data['age'];
            $user->gender = $data['gender'];
            $user->save();
        } else {
            $user            = new MerchantPromotionDatabase();
            $user->name      = $data['name'];
            $user->email     = $data['email'];
            $user->age       = $data['age'];
            $user->gender    = $data['gender'];
            $user->mobile    = $number;
            $user->detail_id = $branch_id;
            $user->save();
        }
    }


}

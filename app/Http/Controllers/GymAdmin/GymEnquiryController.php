<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Models\GymEnquiries;
use App\Models\GymEnquiriesFollowUp;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use DataTables;
use PDF;

class GymEnquiryController extends GymAdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->data['manageMenu']      = 'active';
        $this->data['showenquiryMenu'] = 'active';
    }

    public function index()
    {
        if (!$this->data['user']->can("view_enquiry")) {
            return App::abort(401);
        }

        $this->data['title'] = "Leads";
        return View::make('gym-admin.gymenquiry.index', $this->data);
    }

    public function ajaxCreate()
    {
        if (!$this->data['user']->can("view_enquiry")) {
            return App::abort(401);
        }
        $enquiry = GymEnquiries::select('customer_name', 'customer_mname','customer_lname','mobile', 'email', 'occupation', 'previous_follow_up', 'next_follow_up','is_client', 'id')
            ->where('detail_id', '=', $this->data['user']->detail_id);
        return Datatables::of($enquiry)
            ->addIndexColumn()
            ->editColumn('customer_name', function ($row) {
                return ucwords($row->displayFullName());
            })
            ->editColumn('previous_follow_up', function ($row) {
                return $row->previous_follow_up->toFormattedDateString();
            })
            ->editColumn('next_follow_up', function ($row) {
                return $row->next_follow_up->toFormattedDateString();
            })
            ->addColumn('view_follow_up', function ($row) {
                return "<a href='javascript:;' data-enquiry-id='$row->id' class='btn btn-xs green view-follow-up'>View Follow Up</a>";
            })
            ->addColumn('action', function ($row) {
                if($row->is_client == 0){
                    return "<div class=\"btn-group\">
                    <button class=\"btn btn-xs blue dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\" aria-expanded=\"true\"><i class=\"fa fa-gears\"></i><span class=\"hidden-xs hidden-medium\">ACTION</span>
                    <i class=\"fa fa-angle-down\"></i>
                    </button>
                    <ul class=\"dropdown-menu pull-right\" role=\"menu\">
                    <li>
                        <a data-enquiry-id=\"$row->id\" href=\"javascript:;\"  class=\"new-follow-up\"><i class=\"fa fa-plus\"></i> Follow Up</a>
                    </li>
                    <li>
                        <a data-enquiry-id=\"$row->id\" href='" . route('gym-admin.client.register-enquiry', [$row->id]) . "' ><i class=\"fa fa-user-plus\"></i> Register</a>
                    </li>
                    <li>
                        <a href=" . route('gym-admin.enquiry.edit', [$row->id]) . "><i class=\"fa fa-edit\"></i> Edit </a>
                    </li>
                    <li>
                        <a class=\"delete-button\" onClick='deleteModal(" . $row->id . ")' href=\"javascript:;\"><i class=\"fa fa-trash\"></i> Delete </a>
                    </li>

                    </ul>
                </div>";
                }else{
                    return "Already Became Client";
                }
            })
            ->rawColumns(['action','view_follow_up'])
            ->make(true);
    }

    public function create()
    {
        if (!$this->data['user']->can("add_enquiry")) {
            return App::abort(401);
        }

        $this->data['title'] = "Add Enquiry";

        return view('gym-admin.gymenquiry.create', $this->data);
    }

    public function store(Request $request)
    {
        if (!$this->data['user']->can("add_enquiry")) {
            return App::abort(401);
        }

        $validator         = Validator::make($request->all(), GymEnquiries::$rules);
        $followUpValidator = Validator::make($request->all(), GymEnquiriesFollowUp::$rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->route('gym-admin.enquiry.create')->withErrors($errors)->withInput();

        } elseif ($followUpValidator->fails()) {
            $errors = $followUpValidator->errors();
            return redirect()->route('gym-admin.enquiry.create')->withErrors($errors)->withInput();

        } else {
            $inputData = $request->all();

            $inputData['detail_id'] = $this->data['user']->detail_id;

            if(isset($inputData['dob'])) {
                $inputData['dob'] = Carbon::createFromFormat('m/d/Y', $inputData['dob']);
            }
            $inputData['enquiry_date'] = Carbon::createFromFormat('m/d/Y', $inputData['enquiry_date']);

            $followUpData = [
                'packages_offered'  => $inputData['packages_offered'],
                'remark'            => $inputData['remark'],
                'package_amount'    => $inputData['package_amount'],
                'counselor_name'    => $this->data['user']->username,
                'follow_up_date'    => Carbon::today('Asia/Kathmandu'),
                'next_follow_up_on' => Carbon::createFromFormat('m/d/Y', $inputData['next_follow_up_on'])
            ];

            unset($inputData['packages_offered']);
            unset($inputData['remark']);
            unset($inputData['package_amount']);
            unset($inputData['counselor_name']);
            unset($inputData['follow_up_date']);
            unset($inputData['next_follow_up_on']);

            $gymEnquiry = GymEnquiries::create($inputData);

            $followUpData['gym_enquiry_id'] = $gymEnquiry->id;

            // Insert follow up details
            GymEnquiriesFollowUp::create($followUpData);

            // Save follow up dates to gym_enquiry table
            $gymEnquiry->previous_follow_up = $followUpData['follow_up_date'];
            $gymEnquiry->next_follow_up     = $followUpData['next_follow_up_on'];
            $gymEnquiry->save();

            if(isset($request->email)) {
                $data = [
                    'name'   => $request->customer_name.' '.$request->customer_mname.' '.$request->customer_lname,
                    'email'  => $request->email,
                    'number' => $request->mobile,
                    'age'    => $request->age,
                    'gender' => $request->sex,
                ];
                $this->addPromotionDatabase($data);
            }

            return redirect()->to(route('gym-admin.enquiry.index'))->with('message', 'Enquiry information added successfully.');

        }
    }

    public function edit($id)
    {

        if (!$this->data['user']->can("edit_enquiry")) {
            return App::abort(401);
        }

        $this->data['title'] = "Edit Enquiry";

        $this->data['enquiry'] = GymEnquiries::where('detail_id', '=', $this->data['user']->detail_id)->find($id);

        return view('gym-admin.gymenquiry.edit', $this->data);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        if (!$this->data['user']->can("edit_enquiry")) {
            return App::abort(401);
        }

        $validator = Validator::make($request->all(), [
            'enquiry_date' => 'required|date',
            'customer_name' => 'required|string',
            'customer_lname' => 'required|string',
            'mobile' => 'required|digits:10|unique:gym_enquiries,mobile,'.$id,
            'email' => 'nullable|email|unique:gym_enquiries,email,'.$id,
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->route('gym-admin.enquiry.edit',$id)->withErrors($errors)->withInput();
        } else {
            $inputData                   = $request->all();
            $enquiry                     = GymEnquiries::find($id);
            $enquiry->enquiry_date       = Carbon::createFromFormat('m/d/Y', $inputData['enquiry_date']) ?? '';
            $enquiry->customer_name      = $inputData['customer_name'];
            $enquiry->customer_mname      = $inputData['customer_mname'];
            $enquiry->customer_lname      = $inputData['customer_lname'];
            $enquiry->address            = $inputData['address'];
            $enquiry->mobile             = $inputData['mobile'];
            $enquiry->email              = $inputData['email'];
            $enquiry->age                = $inputData['age'];
            if(isset($inputData['dob'])) {
                $enquiry->dob       = Carbon::createFromFormat('m/d/Y', $inputData['dob']);
            }
            $enquiry->sex                = $inputData['sex'];
            $enquiry->height_feet        = $inputData['height_feet'];
            $enquiry->height_inches      = $inputData['height_inches'];
            $enquiry->weight             = $inputData['weight'];
            $enquiry->occupation         = $inputData['occupation'];
            $enquiry->occupation_details = $inputData['occupation_details'];
            $enquiry->come_to_know       = $inputData['come_to_know'];
            $enquiry->customer_goal      = $inputData['customer_goal'];
            $enquiry->weight_loss_amount = $inputData['weight_loss_amount'];
            $enquiry->weight_gain_amount = $inputData['weight_gain_amount'];
            $enquiry->exercise_regularly = $inputData['exercise_regularly'];
            $enquiry->exercise_type      = $inputData['exercise_type'];
            $enquiry->gyming_where       = $inputData['gyming_where'];
            $enquiry->gyming_since       = $inputData['gyming_since'];
            $enquiry->save();
            return redirect()->route('gym-admin.enquiry.index')->with('message', 'Enquiry information updated successfully.');
        }

    }

    public function removeEnquiry($id)
    {
        if (!$this->data['user']->can("delete_enquiry")) {
            return App::abort(401);
        }

        $this->data['enquiry'] = GymEnquiries::select('customer_name', 'id')->where('id', '=', $id)->first();
        return View::make('gym-admin.gymenquiry.destroy', $this->data);
    }

    public function destroy($id)
    {
        if (!$this->data['user']->can("delete_enquiry")) {
            return App::abort(401);
        }

        $enquiry = GymEnquiries::gymEnquiry($this->data['user']->detail_id, $id);
        $enquiry->delete();
        return Reply::success("Enquiry Removed successfully");
    }

    public function followModal($id)
    {
        $this->data['enquiry'] = GymEnquiries::gymEnquiry($this->data['user']->detail_id, $id);
        return view('gym-admin.gymenquiry.follow_modal', $this->data);
    }

    public function saveFollowUp(Request $request)
    {
        $followUpValidator = Validator::make($inputData = $request->all(), GymEnquiriesFollowUp::$rules);

        if ($followUpValidator->fails()) {
            return Reply::formErrors($followUpValidator);
        } else {
            $gymEnquiry = GymEnquiries::gymEnquiry($this->data['user']->detail_id, $inputData['enquiry_id']);

            $inputData['gym_enquiry_id']    = $gymEnquiry->id;
            $inputData['follow_up_date']    = Carbon::today('Asia/Kathmandu');
            $inputData['next_follow_up_on'] = Carbon::createFromFormat('m/d/Y', $inputData['next_follow_up_on']);
            unset($inputData['enquiry_id']);

            // Insert follow up details
            GymEnquiriesFollowUp::create($inputData);

            // Save follow up dates to gym_enquiry table
            $gymEnquiry->previous_follow_up = $inputData['follow_up_date'];
            $gymEnquiry->next_follow_up     = $inputData['next_follow_up_on'];
            $gymEnquiry->save();

            return Reply::success('Follow up created successfully.');
        }

    }

    public function viewFollowModal($id)
    {
        $this->data['enquiry'] = GymEnquiries::gymEnquiry($this->data['user']->detail_id, $id);
        $this->data['follows'] = GymEnquiriesFollowUp::gymEnquiryFollowUps($id);
        return view('gym-admin.gymenquiry.view_follow_modal', $this->data);
    }

    public function downloadQrCode(){
        $pdf = PDF::loadView('gym-admin.gymenquiry.qrcode',$this->data);
        return $pdf->download('qrcode.pdf');
    }

}

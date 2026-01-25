<?php

namespace App\Http\Controllers\Customer;

use App\Classes\Reply;
use App\Http\Requests\CustomerApp\Profile\StoreProfileRequest;
use App\Models\GymClient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class CustomerProfileController extends CustomerBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('customer-app.profile.index', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProfileRequest $request)
    {
        $client = GymClient::find($this->data['customerValues']->id);
        if($request->get('dob')) {
            $dob = Carbon::createFromFormat('m/d/Y', $request->get('dob'))->format('Y-m-d');
        }
        if($request->get('anniversary')) {
            $anniversary = Carbon::createFromFormat('m/d/Y', $request->get('anniversary'))->format('Y-m-d');
        }
        if($request->get('password')) {
            $password = Hash::make($request->get('password'));
        }
        if($request->hasFile('file')) {
           $this->uploadImage($request);
        }
        $client->update([
            'first_name' => $request->get('first_name'),
            'middle_name' => $request->get('middle_name'),
            'last_name' => $request->get('last_name'),
            'mobile' => $request->get('mobile'),
            'emergency_contact' => $request->get('emergency_contact'),
            'email' => $request->get('email'),
            'gender' => $request->get('gender'),
            'marital_status' => $request->get('marital_status'),
            'height_feet' => $request->get('height_feet'),
            'height_inches' => $request->get('height_inches'),
            'weight' => $request->get('weight'),
            'fat' => $request->get('fat'),
            'chest' => $request->get('chest'),
            'waist' => $request->get('waist'),
            'arms' => $request->get('arms'),
            'occupation' => $request->get('occupation'),
            'occupation_details' => $request->get('occupation_details'),
            'address' => $request->get('address'),
            'blood_group' => $request->get('blood_group'),
            'dob' => $dob ?? $client->dob,
            'password' => $password ?? $client->password,
            'anniversary' => $anniversary ?? $client->anniversary,
        ]);
        return Reply::success('Profile Updated successfully');
    }

    public function uploadImage(Request $request)
    {
        if ($request->ajax()) {
            $id = $this->data['customerValues']->id;
            $output = [];
            $image = request()->file('file');

            $x = intval($request->xCoordOne);
            $y = intval($request->yCoordOne);
            $width = intval($request->profileImageWidth);
            $height = intval($request->profileImageHeight);

            $extension = request()->file('file')->getClientOriginalExtension();
            $filename  = $id."-".rand(10000,99999).".".$extension;

            if($this->data['gymSettings']->local_storage == 0) {
                $destinationPathMaster = "/uploads/profile_pic/master/$filename";
                $destinationPathThumb = "/uploads/profile_pic/thumb/$filename";


                $image1 = Image::make($image->getRealPath())
                    ->crop($width, $height, $x, $y)
                    ->resize(206, 207);

                $this->uploadImageS3($image1, $destinationPathMaster);

                $image2 = Image::make($image->getRealPath())
                    ->crop($width, $height, $x, $y)
                    ->resize(40, 40);

                $this->uploadImageS3($image2, $destinationPathThumb);
            } else {
                if (!file_exists(public_path()."/uploads/profile_pic/master/") &&
                    !file_exists(public_path()."/uploads/profile_pic/thumb/")) {
                    File::makeDirectory(public_path()."/uploads/profile_pic/master/", $mode = 0777, true, true);
                    File::makeDirectory(public_path()."/uploads/profile_pic/thumb/", $mode = 0777, true, true);
                }

                $destinationPathMaster = public_path()."/uploads/profile_pic/master/$filename";
                $destinationPathThumb = public_path()."/uploads/profile_pic/thumb/$filename";
                $image1 = Image::make($image->getRealPath())
                    ->resizeCanvas($width, $height, 'center', false, 'rgba(0, 0, 0, 0)');
                $image1->save($destinationPathMaster);

                $image2 = Image::make($image->getRealPath())
                    ->resizeCanvas($width, $height, 'center', false, 'rgba(0, 0, 0, 0)')
                    ->resize(40, 40);
                $image2->save($destinationPathThumb);
            }


            $forUpdate = [
                'image' => $filename
            ];

            $profile = GymClient::find($id);
            $profile->update($forUpdate);

            $output['image'] = $filename;
            return json_encode($output);
        }
        else
        {
            return "Illegal request method";
        }

    }

    public function uploadImageS3($imageMake, $filePath)
    {
        if (get_class($imageMake) === 'Intervention\Image\Image') {
            Storage::put($filePath, $imageMake->stream()->__toString(), 'public');
        } else {
            Storage::put($filePath, fopen($imageMake, 'r'), 'public');
        }
    }

    public function uploadWebcamImage($id)
    {
        $img = request()->input('webcam') ?? request()->file('webcam');
        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image = base64_decode($image_parts[1]);
        $fileName = $id . "-" . rand(10000, 99999) .'.'. $image_type;

        if($this->data['gymSettings']->local_storage == 0) {
            $destinationPathMaster = "profile_pic/master/$fileName";
            $destinationPathThumb = "profile_pic/thumb/$fileName";

            $image1 = Image::make($image)->resize(206, 155);

            $this->uploadImageS3($image1, $destinationPathMaster);

            $image2 = Image::make($image)->resize(35, 34);

            $this->uploadImageS3($image2, $destinationPathThumb);
        } else {
            if (!file_exists(public_path()."/uploads/profile_pic/master/") &&
                !file_exists(public_path()."/uploads/profile_pic/thumb/")) {
                File::makeDirectory(public_path()."/uploads/profile_pic/master/", $mode = 0777, true, true);
                File::makeDirectory(public_path()."/uploads/profile_pic/thumb/", $mode = 0777, true, true);
            }

            $destinationPathMaster = public_path()."/uploads/profile_pic/master/$fileName";
            $destinationPathThumb = public_path()."/uploads/profile_pic/thumb/$fileName";
            $image1 = Image::make($image)->resize(206, 155);
            $image1->save($destinationPathMaster);

            $image2 = Image::make($image)->resize(35, 34);
            $image2->save($destinationPathThumb);
        }


        $gym_client = GymClient::find($id);
        $gym_client->image = $fileName;
        $gym_client->save();

        $output['image'] = $fileName;

        return $output;
    }
}

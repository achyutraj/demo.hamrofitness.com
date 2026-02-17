<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Http\Requests\GymAdmin\Image\ImageRequest;
use App\Models\BusinessBranch;
use App\Models\CommonDetails;
use App\Models\MobileApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class MobileAppController extends GymAdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['manageMenu'] = 'active';
        $this->data['showmobileAppMenu'] = 'active';
    }

    public function index()
    {
        if (!$this->data['user']->can("mobile_app") && $this->data['user']->id == 1) {
            return App::abort(401);
        }

        $this->data['title'] = "Mobile App";
        return view('gym-admin.mobile_app.index', $this->data);
    }

    public function ajaxCreate()
    {
        if (!$this->data['user']->can("mobile_app") && $this->data['user']->id == 1) {
            return App::abort(401);
        }
        $mobileApps = MobileApp::where('detail_id', '=', $this->data['user']->detail_id);
        return Datatables::of($mobileApps)
            ->addIndexColumn()
            ->editColumn('detail_id', function ($row) {
                return $row->commonDetails->title;
            })
            ->editColumn('about', function ($row) {
                return $row->about;
            })
            ->editColumn('services', function ($row) {
                return $row->services;
            })
            ->editColumn('price_plan', function ($row) {
                return $row->price_plan;
            })
            ->editColumn('logo', function ($row) {
                if (!empty($row->logo)) {
                    return '<img style="width:50px;height:50px;" class="img-circle" src="' . asset('/uploads/mobile_app/').'/' . $row->logo . '" alt="" />';
                } else {
                    return $row->commonDetails->title;
                }
            })
            ->addColumn('action', function ($row) {
                return "<div class=\"btn-group\">
                <button class=\"btn btn-xs blue dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\" aria-expanded=\"true\"><span class=\"hidden-xs\">ACTION</span>
                <i class=\"fa fa-angle-down\"></i>
                </button>
                <ul class=\"dropdown-menu pull-right\" role=\"menu\">
                <li>
                    <a href=" . route('gym-admin.mobile-app.edit', [$row->uuid]) . "><i class=\"fa fa-edit\"></i> Edit </a>
                </li>
                </ul>
            </div>";
            })
            ->rawColumns(['action', 'detail_id', 'logo','services','about','price_plan'])
            ->make(true);
    }

    public function create()
    {
        if (!$this->data['user']->can("mobile_app") && $this->data['user']->id == 1) {
            return App::abort(401);
        }
        $this->data['title'] = "Add Mobile App";
        $this->data['branches'] = BusinessBranch::all();
        return view('gym-admin.mobile_app.create', $this->data);
    }

    public function store(Request $request)
    {
        if (!$this->data['user']->can("mobile_app") && !$this->data['user']->id == 1) {
            return App::abort(401);
        }
        $validator = Validator::make($request->all(), MobileApp::$rules);

        if ($validator->fails()) {
            return Reply::formErrors($validator);
        }
        $inputData = $request->all();
        $app = MobileApp::create($inputData);
        return redirect()->to(route('gym-admin.mobile-app.edit',$app->uuid))->with('message', 'Mobile App information added.');
    }

    public function uploadImage(ImageRequest $request)
    {
        if ($request->ajax()) {
            $id = $this->data['user']->id;

            $output = [];
            $image = request()->file('file');

            $x = intval($request->xCoordOne);
            $y = intval($request->yCoordOne);
            $width = intval($request->profileImageWidth);
            $height = intval($request->profileImageHeight);

            $extension = request()->file('file')->getClientOriginalExtension();
            $filename = $id . "-" . rand(10000, 99999) . "." . $extension;

            if (
                !is_null($this->data['gymSettings']->file_storage) || $this->data['gymSettings']->file_storage != '' ||
                !is_null($this->data['gymSettings']->aws_key) || $this->data['gymSettings']->aws_key != '' ||
                !is_null($this->data['gymSettings']->aws_secret) || $this->data['gymSettings']->aws_secret != '' ||
                !is_null($this->data['gymSettings']->aws_region) || $this->data['gymSettings']->aws_region != '' ||
                !is_null($this->data['gymSettings']->aws_bucket) || $this->data['gymSettings']->aws_bucket != ''
            ) {
                $destinationPathMaster = "/uploads/mobile_app/master/$filename";
                $destinationPathThumb = "/uploads/mobile_app/thumb/$filename";

                $image1 = Image::make($image->getRealPath())
                    ->crop($width, $height, $x, $y)
                    ->resize(206, 207);

                $this->uploadImageS3($image1, $destinationPathMaster);

                $image2 = Image::make($image->getRealPath())
                    ->crop($width, $height, $x, $y)
                    ->resize(40, 40);

                $this->uploadImageS3($image2, $destinationPathThumb);
            } else {
                if (!file_exists(public_path() . "/uploads/mobile_app/master/") &&
                    !file_exists(public_path() . "/uploads/mobile_app/thumb/")) {
                    File::makeDirectory(public_path() . "/uploads/mobile_app/master/", $mode = 0777, true, true);
                    File::makeDirectory(public_path() . "/uploads/mobile_app/thumb/", $mode = 0777, true, true);
                }

                $destinationPathMaster = public_path() . "/uploads/mobile_app/master/$filename";
                $destinationPathThumb = public_path() . "/uploads/mobile_app/thumb/$filename";
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

            $profile = MobileApp::find($id);
            $profile->update($forUpdate);

            $output['image'] = $filename;
            return json_encode($output);
        } else {
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

    public function edit(MobileApp $mobileApp)
    {
        if (!$this->data['user']->can("mobile_app")) {
            return App::abort(401);
        }
        $this->data['title'] = "Edit Mobile App";
        $this->data['mobileApp'] = $mobileApp;
        if($this->data['user']->id == 1) {
            $this->data['branches'] = BusinessBranch::all();
        }else{
            $this->data['branches'] = BusinessBranch::where('detail_id',$this->data['user']->detail_id)->get();
        }
        return view('gym-admin.mobile_app.edit', $this->data);
    }

    public function update(Request $request)
    {
        if (!$this->data['user']->can("mobile_app")) {
            return App::abort(401);
        }
        $id = $request->get('mobile_app');
        $type = $request->get('type');
        if(!$type){
            $validator = Validator::make($request->all(), [
                'address' => 'required',
                'services' => 'required',
                'about' => 'required',
                'price_plan' => 'required',
                'contact_mail' => 'required|email|unique:mobile_apps,contact_mail,'.$id,
            ]);
            if ($validator->fails()) {
                return Reply::formErrors($validator);
            }
        }
        $app = MobileApp::find($id);
        if(!$type) {
            $app->detail_id = $request->get('detail_id');
            $app->address = $request->get('address');
            $app->about = $request->get('about');
            $app->services = $request->get('services');
            $app->price_plan = $request->get('price_plan');
            $app->contact_mail = $request->get('contact_mail');
        }
        $app->fb_url = $request->get('fb_url');
        $app->google_url = $request->get('google_url');
        $app->youtube_url = $request->get('youtube_url');
        $app->twitter_url = $request->get('twitter_url');
        $app->save();
        return Reply::redirect(route('gym-admin.mobile-app.edit',$app->uuid),'Mobile App information updated.');

    }

    public function imagesStore(Request $request)
    {
        if (!$this->data['user']->can("mobile_app")) {
            return App::abort(401);
        }
        $id = $request->get('mobile_app_id');
        $app = MobileApp::find($id);
        if ($request->file('offer_image')) {
            $image = $request->file('offer_image');
            $extension = $request->file('offer_image')->getClientOriginalExtension();
            $imageName = 'offer_image-'.rand(10,50) . '.' . $extension;
            $imagePath = public_path('/uploads/mobile_app/'.$app->offer_image);
            if(File::exists($imagePath) && !is_null($app->offer_image)){
                unlink($imagePath);
            }
            $image->move(public_path('/uploads/mobile_app/'), $imageName);
            $offer_image = $imageName;
        }else{
            $offer_image = $app->offer_image;
        }

        if ($request->file('logo')) {
            $image = $request->file('logo');
            $extension = $request->file('logo')->getClientOriginalExtension();
            $imageName = 'logo-'.rand(10,50) . '.' . $extension;
            $imagePath = public_path('/uploads/mobile_app/'.$app->logo);
            if(File::exists($imagePath) && !is_null($app->logo)){
                unlink($imagePath);
            }
            $image->move(public_path('/uploads/mobile_app/'), $imageName);
            $logo = $imageName;
        }else{
            $logo = $app->logo;
        }
        if ($request->file('banner_image1')) {
            $image = $request->file('banner_image1');
            $extension = $request->file('banner_image1')->getClientOriginalExtension();
            $imageName = 'banner-1-'.rand(10,50) . '.' . $extension;
            $imagePath = public_path('/uploads/mobile_app/'.$app->banner_image1);
            if(File::exists($imagePath) && !is_null($app->banner_image1)){
                unlink($imagePath);
            }
            $image->move(public_path('/uploads/mobile_app/'), $imageName);
            $banner_image1 = $imageName;
        }else{
            $banner_image1 = $app->banner_image1;
        }

        if ($request->file('banner_image2')) {
            $image = $request->file('banner_image2');
            $extension = $request->file('banner_image2')->getClientOriginalExtension();
            $imageName = 'banner-2-'.rand(10,50) . '.' . $extension;
            $imagePath = public_path('/uploads/mobile_app/'.$app->banner_image2);
            if(File::exists($imagePath) && !is_null($app->banner_image2)){
                unlink($imagePath);
            }
            $image->move(public_path('/uploads/mobile_app/'), $imageName);
            $banner_image2 = $imageName;
        }else{
            $banner_image2 = $app->banner_image2;
        }

        if ($request->file('banner_image3')) {
            $image = $request->file('banner_image3');
            $extension = $request->file('banner_image3')->getClientOriginalExtension();
            $imagePath = public_path('/uploads/mobile_app/'.$app->banner_image3);
            if(File::exists($imagePath) && !is_null($app->banner_image3)){
                unlink($imagePath);
            }
            $imageName = 'banner-3-'.rand(10,50) . '.' . $extension;
            $image->move(public_path('/uploads/mobile_app/'), $imageName);
            $banner_image3 = $imageName;
        }else{
            $banner_image3 = $app->banner_image3;
        }
        if ($request->file('notice_image')) {
            $image = $request->file('notice_image');
            $extension = $request->file('notice_image')->getClientOriginalExtension();
            $imageName = 'notice_image-'.rand(10,50) . '.' . $extension;
            $imagePath = public_path('/uploads/mobile_app/'.$app->notice_image);
            if(File::exists($imagePath) && !is_null($app->notice_image)){
                unlink($imagePath);
            }
            $image->move(public_path('/uploads/mobile_app/'), $imageName);
            $notice_image = $imageName;
        }else{
            $notice_image = $app->notice_image;
        }

        $app->logo = $logo;
        $app->offer_image = $offer_image;
        $app->notice_image = $notice_image;
        $app->banner_image1 = $banner_image1;
        $app->banner_image2 = $banner_image2;
        $app->banner_image3 = $banner_image3;
        $app->save();
        return redirect()->route('gym-admin.mobile-app.index')->with('message','Mobile App information updated.');
    }

    public function destroy($id)
    {
        if (!$this->data['user']->can("mobile_app") && $this->data['user']->id == 1) {
            return App::abort(401);
        }
        $app = MobileApp::find($id);
        $app->delete();
        return redirect()->to(route('gym-admin.mobile-app.index'))->with('message', 'Mobile App information deleted.');
    }

}

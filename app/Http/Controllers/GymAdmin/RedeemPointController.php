<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\GymAdmin\GymAdminBaseController;
use App\Http\Controllers\Controller;
use App\Models\GymMembership;
use App\Models\RedeemPoint;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;


class RedeemPointController extends GymAdminBaseController
{
    
    public function __construct()
    {
        parent::__construct();
        $this->data['manageMenu'] = 'active';
        $this->data['redeemMenu'] = 'active';
    }

    public function index()
    {
        if (!$this->data['user']->can("view_redeems")) {
            return App::abort(401);
        }
        $this->data['title'] = "All Redeem Offer";

        $this->data['redeems'] = RedeemPoint::where('detail_id', $this->data['user']->detail_id)->get();
        return view('gym-admin.redeems.index', $this->data);
    }

    public function create()
    {
        if (!$this->data['user']->can("add_redeems")) {
            return App::abort(401);
        }
        $this->data['title'] = "Add Redeem Offer";
        $this->data['memberships'] = GymMembership::where('detail_id', $this->data['user']->detail_id)->get();
        return view('gym-admin.redeems.create', $this->data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), RedeemPoint::rules('add'));
        if ($validator->fails()) {
            return Reply::formErrors($validator);
        } else {
            $inputData = $request->all();
            $inputData['detail_id'] = $this->data['user']->detail_id;
            $inputData['membership_id'] = $request->get('membership');
            RedeemPoint::create($inputData);
            return Reply::redirect(route('gym-admin.redeems.index'), "Redeem Offer added successfully");
        }
    }

    public function edit($id)
    {
        if (!$this->data['user']->can("edit_redeems")) {
            return App::abort(401);
        }
        $this->data['title'] = "Edit Offer";
        $this->data['redeem'] = RedeemPoint::find($id);
        return view('gym-admin.redeems.edit', $this->data);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        if (!$this->data['user']->can("edit_redeems")) {
            return App::abort(401);
        }
        $validator = Validator::make($request->all(), RedeemPoint::rules('add'));
        if ($validator->fails()) {
            return Reply::formErrors($validator);
        } else {
            $inputData = $request->all();

            //check weather other active or not
            $other = RedeemPoint::active()->whereDate('end_date','>=',today())
                ->where('detail_id',$this->data['user']->detail_id)->count();
            if($inputData['status'] == 1 && $other > 0){
                return Reply::error("You have already existing offer. Please update status.");
            }
            $redeem = RedeemPoint::find($id);
            $redeem->title = $inputData['title'];
            $redeem->redeem_points = $inputData['redeem_points'];
            $redeem->membership_id = $inputData['membership'];
            $redeem->start_date = $inputData['start_date'];
            $redeem->end_date = $inputData['end_date'];
            $redeem->status = $inputData['status'];
            $redeem->save();

            return Reply::redirect(route('gym-admin.redeems.index'), "Redeem updated successfully");
        }
    }

    public function destroy($id)
    {
        if (!$this->data['user']->can("delete_redeems")) {
            return App::abort(401);
        }
        if(request()->ajax()){
            $redeem = RedeemPoint::find($id);
            $redeem->delete();

            return Reply::redirect(route('gym-admin.redeems.index'), "Redeem deleted successfully");
        }
    }
}

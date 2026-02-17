<?php

namespace App\Http\Controllers\GymAdmin;

use App\Models\Activity;
use App\Classes\Reply;
use App\Models\GymSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LevelActivityController extends GymAdminBaseController
{
    public function create(Request $request){
        $validate = Validator::make($request->all(),GymSetting::rules('activity'));
        if($validate->fails())
        {
            return Reply::formErrors($validate);
        }
        $activity = new Activity();
        $activity->branch_id = $this->data['user']->detail_id;
        $activity->level = $request->level;
        $activity->activity = json_encode($request->activity);
        $activity->save();
        return redirect()->back()->with('message','Activity added successfully');
    }

    public function update(Request $request,$id){
        $validate = Validator::make($request->all(),GymSetting::rules('activity'));
        if($validate->fails())
        {
            return Reply::formErrors($validate);
        }
        $activity = Activity::findorfail($id);
        $activity->branch_id = $this->data['user']->detail_id;
        $activity->level = $request->level;
        $activity->activity = json_encode($request->activity);
        $activity->save();
        return redirect()->back()->with('message','Activity updated successfully');
    }

    public function delete($id){
        $activity = Activity::find($id);
        $activity->delete();
        return Reply::success("Activity deleted successfully.");
    }
}

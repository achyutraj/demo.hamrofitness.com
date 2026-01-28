<?php

namespace App\Http\Controllers\Device;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GymAdmin\GymAdminBaseController;
use App\Models\Department;
use App\Models\Device;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class DepartmentAndShiftController extends GymAdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['title'] = 'Device Management';
    }
    public function index(){
        if (!$this->data['user']->can("manage_device")) {
            return App::abort(401);
        }
        $this->data['departments'] = Department::where('detail_id',$this->data['user']->detail_id)->get();
        $this->data['devices'] = Device::where('detail_id',$this->data['user']->detail_id)->get();
        $this->data['shifts'] = Shift::where('detail_id',$this->data['user']->detail_id)->get();
        return view('devices.index',$this->data);
    }

    public function shiftStore(Request $request)
    {
        if (!$this->data['user']->can("manage_device")) {
            return App::abort(401);
        }
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }
        Shift::create([
            'name' => $request->get('name'),
            'slug' => Str::slug($request->get('name')),
            'from_time' => $request->get('from_time'),
            'to_time' => $request->get('to_time'),
            'detail_id' => $this->data['user']->detail_id
        ]);
        return redirect()->back()->with('message', 'Device shift added successfully');
    }

    public function shiftUpdate(Request $request, $id)
    {
        if (!$this->data['user']->can("manage_device")) {
            return App::abort(401);
        }
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }
        $shift = Shift::findOrFail($id);
        $shift->update([
            'name' => $request->get('name'),
            'slug' => Str::slug($request->get('name')),
            'from_time' => $request->get('from_time'),
            'to_time' => $request->get('to_time'),
        ]);
        return redirect()->back()->with('message', 'Device shift updated successfully');
    }

    public function shiftDelete($id)
    {
        if (!$this->data['user']->can("manage_device")) {
            return App::abort(401);
        }
        $shift = Shift::findOrFail($id);
        $shift->delete();
        return Reply::success("Device shift deleted successfully.");
    }

    public function departmentStore(Request $request)
    {
        if (!$this->data['user']->can("manage_device")) {
            return App::abort(401);
        }
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }
        Department::create([
            'name' => $request->get('name'),
            'detail_id' => $this->data['user']->detail_id
        ]);
        return redirect()->back()->with('message', 'Device department added successfully');
    }

    public function departmentUpdate(Request $request, $id)
    {
        if (!$this->data['user']->can("manage_device")) {
            return App::abort(401);
        }
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }
        $depart = Department::findOrFail($id);
        $depart->name = $request->get('name');
        $depart->save();
        return redirect()->back()->with('message', 'Device department updated successfully');
    }

    public function departmentDelete($id)
    {
        if (!$this->data['user']->can("manage_device")) {
            return App::abort(401);
        }
        $depart = Department::findOrFail($id);
        if(!is_null($depart) && $depart->device_info->count() > 0) {
            return Reply::error("Device department has device.");
        }
        $depart->delete();
        return Reply::success("Device department deleted successfully.");
    }
}

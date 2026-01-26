<?php

namespace App\Http\Controllers\Device;

use App\Classes\Reply;
use App\Helpers\ADMSHelper;
use App\Http\Controllers\GymAdmin\GymAdminBaseController;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeviceController extends GymAdminBaseController
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'code' => 'required|string',
            'serial_num' => 'required|string',
            'port_num' => 'required|string',
            'department' => 'required',
            'vendor_name' => 'required|string',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }
        $device = Device::create([
            'name' => $request->get('name'),
            'code' => $request->get('code'),
            'ip_address' => $request->get('ip_address'),
            'serial_num' => $request->get('serial_num'),
            'port_num' => $request->get('port_num'),
            'device_model' => $request->get('device_model'),
            'device_type' => $request->get('device_type'),
            'vendor_name' => $request->get('vendor_name'),
            'detail_id' => $this->data['user']->detail_id,
        ]);
        $device->departments()->sync($request->get('department'));
        return redirect()->back()->with('message', 'Device added successfully');

    }

    public function show($id)
    {
        $device = Device::findOrFail($id);
        $deviceStatus = ADMSHelper::checkDeviceStatus($device->code);
        if ($deviceStatus !== false) {
            $responseData = $deviceStatus->getData(true);
            $this->data['devices'] = $responseData['data'];
        }else{
             $this->data['devices'] = null;
        }

        return view('devices.adms.index',$this->data);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'code' => 'required|string',
            'serial_num' => 'required|string',
            'port_num' => 'required|string',
            'department' => 'required',
            'vendor_name' => 'required|string',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }
        $device = Device::findOrFail($id);
        $device->update([
            'name' => $request->get('name'),
            'code' => $request->get('code'),
            'ip_address' => $request->get('ip_address'),
            'serial_num' => $request->get('serial_num'),
            'port_num' => $request->get('port_num'),
            'device_model' => $request->get('device_model'),
            'device_type' => $request->get('device_type'),
            'device_status' => $request->get('device_status'),
            'vendor_name' => $request->get('vendor_name'),
        ]);
        $device->departments()->sync($request->get('department'));

        return redirect()->back()->with('message', 'Device updated successfully');
    }

    public function destroy($id)
    {
        $device = Device::findOrFail($id);
        if($device->clients->count() > 0) {
            return Reply::error("Device has client.");
        }
        $device->delete();
        return Reply::success("Device deleted successfully.");
    }

    public function checkDeviceStatus()
    {
        $device = Device::where('detail_id',$this->data['user']->detail_id)->where('device_status',1)->first();
        $deviceStatus = ADMSHelper::checkDeviceStatus($device->code);
        if ($deviceStatus !== false) {
            $responseData = $deviceStatus->getData(true);
            $this->data['devices'] = $responseData['data'];
        }else{
            $this->data['devices'] = null;
        }
        return view('devices.adms.index',$this->data);
    }

    public function clearDeviceAttendanceLogs($id)
    {
        $device = Device::findOrFail($id);
        $status = ADMSHelper::clearAttendanceLogFromDevice($device->serial_num);
        if($status){
            return Reply::success("Device Attendance log deleted successfully.");
        }else{
            return Reply::error("Unable to clear device Attendance log.");
        }

    }
}

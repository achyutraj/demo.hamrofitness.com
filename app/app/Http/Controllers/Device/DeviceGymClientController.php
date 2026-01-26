<?php

namespace App\Http\Controllers\Device;

use App\Classes\Reply;
use App\Helpers\ADMSHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GymAdmin\GymAdminBaseController;
use App\Models\Device;
use App\Models\Shift;
use App\Models\GymClient;
use App\Models\SyncLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class DeviceGymClientController extends GymAdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['manageMenu'] = 'active';
        $this->data['customerBioMenu'] = 'active';
    }

    public function index(){
        if (!$this->data['user']->can("add_biometrics") && $this->data['common_details']->has_device == 1) {
            return App::abort(401);
        }
        $this->data['title'] = "Clients Biometrics";
        $this->data['devices'] = Device::where('device_status', 1)->where('detail_id',$this->data['user']->detail_id)
                ->get();
        if( $this->data['devices']->count() > 1){
            $this->data['clients'] = GymClient::GetClients($this->data['user']->detail_id)->has('devices')->get();
            return view('devices.biometrics.multi-device-index', $this->data);
        }else{
            return view('devices.biometrics.index', $this->data);
        }

    }

    public function ajax_create(){
        if (!$this->data['user']->can("add_biometrics") && $this->data['common_details']->has_device == 1) {
            return App::abort(401);
        }

        $gym_clients = GymClient::GetClients($this->data['user']->detail_id)->has('devices')->get();

        return Datatables::of($gym_clients)
            ->addIndexColumn()
            ->editColumn('first_name', function ($row) {
                if ($row->image != '') {
                    $image_url =  $this->data['profileHeaderPath'] . $row->image ;
                } else {
                    $image_url =  asset('/fitsigma/images/user.svg');
                }
                return '<a href="'. route('gym-admin.client.show', $row->customer_id).'"><img style="width:50px;height:50px;" class="img-circle" src="' . $image_url. '" alt="" /><br>' . $row->fullName .'</a>' ;

            })
            ->addColumn('department', function ($row) {
                $data = '';
                foreach($row->latestDeviceClients()->departments as $depart){
                    $data .= $depart->name.',';
                }
                return $data;
            })
            ->addColumn('device', function ($row) {
                $data = '';
                foreach($row->devices as $device){
                    $data .= $device->name.',';
                }
                return $data;
            })
            ->addColumn('shift', function ($row) {
                $data = '';
                foreach($row->shifts as $shift){
                    $data .= $shift->name.',';
                }
                return $data;
            })
            ->addColumn('door_access', function ($row) {
                $data = '<span class="label label-success">Allowed</span>';
                if($row->is_device_deleted == 1 || $row->is_denied){
                    $data = '<span class="label label-danger">Denied</span>';
                }
                if($row->syncLog?->synced == false){
                    $data .='<br> Not Sync';
                }
                return $data;
            })
            ->addColumn('action', function ($row) {
                $action = '<div class="btn-group">
                    <button class="btn blue btn-xs dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-gears"></i> <span class="hidden-xs hidden-medium">Actions</span>
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <li>
                            <a href="' . route('gym-admin.client.show', $row->customer_id) . '"> <i class="fa fa-edit"></i>Show Profile</a>
                        </li>
                        <li>
                            <a class="remove-user" data-url="' . route('device.biometrics.clientRemoveFromDevice',['clientId'=>$row->customer_id,'deviceId'=>$row->latestDeviceClients()->id]) . '"> <i class="fa fa-trash"></i>Remove</a>
                        </li>
                    ';
                if($row->syncLog?->synced == false){
                    $action .= '<li>
                    <a href="' . route('device.biometrics.syncUser',['clientId'=>$row->customer_id]) . '"> <i class="fa fa-plus"></i>Sync</a>
                </li>';
                }
                if($row->is_device_deleted == 1){
                    $action .= '<li>
                    <a href="' . route('device.biometrics.renewUser',['clientId'=>$row->customer_id]) . '"> <i class="fa fa-recycle"></i>Renew</a>
                </li>';
                }else{
                    $action .= '<li>
                    <a class="denied-user"  data-url="' . route('device.biometrics.clientRemoveFromDeviceOnly',['clientId'=>$row->customer_id,'deviceId'=>$row->latestDeviceClients()->id]) . '"> <i class="fa fa-stop-circle"></i>Denied</a>
                </li>';
                }
                $action .='</ul></div>';
                return $action;
            })
            ->rawColumns(['door_access','action','first_name','department','shift','device'])
            ->make(true);
    }

    public function create($clientId = null){
        if (!$this->data['user']->can("add_biometrics") && $this->data['common_details']->has_device == 1) {
            return App::abort(401);
        }
        $this->data['title'] = "Clients Biometric Create";
        $this->data['devices'] = Device::where('device_status',1)->where('detail_id',$this->data['user']->detail_id)->get();
        $this->data['shifts'] = Shift::where('detail_id',$this->data['user']->detail_id)->get();
        if($clientId != null){
            $this->data['clients'] = GymClient::GetClients($this->data['user']->detail_id)
            ->where('customer_id',$clientId)->get();
        }else{
            $this->data['clients'] = GymClient::getActiveClientWithoutShifts($this->data['user']->detail_id);
        }
        return view('devices.biometrics.create', $this->data);
    }

    public function renewUser($clientId = null){
        if (!$this->data['user']->can("add_biometrics") && $this->data['common_details']->has_device == 1) {
            return App::abort(401);
        }
        $this->data['title'] = "Clients Biometric Renew";
        $this->data['devices'] = Device::where('device_status',1)->where('detail_id',$this->data['user']->detail_id)->get();
        if($clientId != null){
            $this->data['clients'] = GymClient::GetClients($this->data['user']->detail_id)->where('is_device_deleted',1)
                            ->where('customer_id',$clientId)->get();
        }else{
            $this->data['clients'] = GymClient::GetClients($this->data['user']->detail_id)->where('is_device_deleted',1)
                            ->take(10)->get();
        }
        return view('devices.biometrics.renew', $this->data);
    }

    public function store(Request $request){
        if (!$this->data['user']->can("add_biometrics")) {
            return App::abort(401);
        }
        $validate =  Validator::make($request->all(),[
            'devices' => 'required',
            'shifts' => 'required',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return redirect()->back()->with('errors', $errors)->withInput();
        }
        $deviceData = $request->input('devices');
        $shiftData = $request->input('shifts');
        $errors = [];
        foreach ($deviceData as $clientId => $deviceIds) {
            $client = GymClient::findOrFail($clientId);

            // Ensure that $deviceIds is an array
            if (!is_array($deviceIds)) {
                $deviceIds = [$deviceIds];
            }

            foreach ($deviceIds as $deviceId) {
                $device = Device::findOrFail($deviceId);

                // Check if shift data is available for the current client
                if (!isset($shiftData[$clientId])) {
                    $errors[] = "Shift data is missing for client ID: $clientId";
                    continue;
                }

                $userData = [
                    'userId' => $clientId,
                    'name' => $client->fullName,
                    'card' => null,
                    'category' => 0,
                ];

                $status = ADMSHelper::updateUserInfo($device->serial_num, $device->code, $userData);
                $client->shifts()->sync($shiftData[$clientId]);
                $client->devices()->sync($deviceData[$clientId]);
                if ($status === true) {
                    SyncLog::updateOrCreate(
                        [
                            'client_id' => $clientId,
                            'device_id' => $deviceId,
                        ],
                        [
                            'synced' => true,
                            'sync_on' => now(),
                        ]
                    );
                } else {
                    $errors[] = "Failed to update user info for client ID: $clientId with device: {$device->code}";
                    SyncLog::updateOrCreate(
                        [
                            'client_id' => $clientId,
                            'device_id' => $deviceId,
                        ],
                        [
                            'synced' => false,
                            'sync_on' => now(),
                        ]
                    );
                }
            }
        }
        if (!empty($errors)) {
            return redirect()->back()->with('errors', $errors)->withInput();
        }
        if(request()->ajax()){
            return Reply::success("Client Added to Biometric Successfully.");
        }else{
            return redirect()->route('device.biometrics.index')->with('message', 'Client Added to Biometric Successfully');
        }
    }

    public function renewUserStore(Request $request){
        if (!$this->data['user']->can("add_biometrics")) {
            return App::abort(401);
        }
        $validate =  Validator::make($request->all(),[
            'devices' => 'required',
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return redirect()->back()->with('errors', $errors)->withInput();
        }
        $deviceData = $request->input('devices');
        $errors = [];
        foreach ($deviceData as $clientId => $deviceIds) {
            $client = GymClient::findOrFail($clientId);

            // Ensure that $deviceIds is an array
            if (!is_array($deviceIds)) {
                $deviceIds = [$deviceIds];
            }

            foreach ($deviceIds as $deviceId) {
                $device = Device::findOrFail($deviceId);
                $status = ADMSHelper::sendUserDevice($clientId,$device->code);
                if($status === true){
                    $client->update([
                        'is_device_deleted' => false,
                        'is_denied' => false,
                        'is_expired' => false,
                    ]);

                    DB::table('device_gym_clients')->updateOrInsert(
                        [
                            'client_id' => $client->id,
                            'device_id' => $device->id,
                        ],
                        [
                            'is_device_deleted' => false,
                            'is_denied' => false,
                            'is_expired' => false,
                        ]
                    );
                }else {
                    $errors[] = "Failed to renew user info for client ID: $clientId with device : {$device->code}";
                }
            }
        }
        if (!empty($errors)) {
            return redirect()->back()->with('errors', $errors)->withInput();
        }
        if(request()->ajax()){
            return Reply::success("Renew Client to Biometric Successfully.");
        }else{
            return redirect()->route('device.biometrics.index')->with('message', 'Renew Client to Biometric Successfully');
        }
    }

    public function addCardForm(){
        if (!$this->data['user']->can("add_biometrics") && $this->data['common_details']->has_device == 1) {
            return App::abort(401);
        }
        $this->data['title'] = "Clients Card Add";
        $this->data['devices'] = Device::where('device_status',1)->where('detail_id',$this->data['user']->detail_id)->get();
        $this->data['clients'] = GymClient::getActiveClientWithCardIsNull($this->data['user']->detail_id);
        return view('devices.biometrics.card', $this->data);
    }

    public function createOrUpdateUserInfo(Request $request){
        $this->validate($request,[
            'device' => 'required'
        ]);

        $device = Device::findOrFail($request->get('device'));
        $cardData = $request->input('card');
        foreach($cardData as $clientId => $card){
            $client = GymClient::findOrFail($clientId);
            $client->update([
                'card' => $card,
            ]);
            $userData = [
                'userId' => $clientId,
                'name' => $client->fullName,
                'card' => $card,
                'category' => 0,
            ];
            ADMSHelper::updateUserInfo($device->serial_num,$device->code,$userData);
        }
        return redirect()->route('device.biometrics.index')->with('message', 'Client Card to Device Successfully');

    }

    //remove user from both device and system
    public function clientRemoveFromDevice($clientId,$deviceId){
        $device = Device::findOrFail($deviceId);
        $client = GymClient::findOrFail($clientId);
        $status = ADMSHelper::deleteUserFromDevice($clientId,$device->code);
        if($status === true){
            $client->update([
                'is_device_deleted' => true,
            ]);

            DB::table('device_gym_clients')->updateOrInsert(
                [
                    'client_id' => $client->id,
                    'device_id' => $device->id,
                ],
                [
                    'is_device_deleted' => true,
                ]
            );

            $device->clients()->detach($clientId);
            $client->shifts()->detach();
            return Reply::success("Client remove from device successfully.");
        }else{
            return Reply::error("Unable to connect to device.");
        }

    }

    //remove user from device only but not from system
    public function clientRemoveFromDeviceOnly($clientId,$deviceId){
        $device = Device::findOrFail($deviceId);
        $client = GymClient::findOrFail($clientId);
        $status = ADMSHelper::deleteUserFromDevice($clientId,$device->code);
        if($status === true){
            $client->update([
                'is_device_deleted' => true,
                'is_denied' => true
            ]);

            DB::table('device_gym_clients')->updateOrInsert(
                [
                    'client_id' => $client->id,
                    'device_id' => $device->id,
                ],
                [
                    'is_denied' => true,'is_device_deleted' => true,
                ]
            );

            return Reply::success("Client denied successfully.");
        }else{
            return Reply::error("Unable to connect to device.");
        }

    }

}

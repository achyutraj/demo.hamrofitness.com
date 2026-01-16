<?php
namespace App\Http\Controllers\GymAdmin;
use App\Classes\Reply;
use App\Jobs\BulkAdminSms;
use App\Jobs\BulkCustomerSms;
use App\Jobs\BulkEmployeeSms;
use App\Jobs\SendAdminSms;
use App\Models\CustomerSms;
use App\Models\Employ;
use App\Models\EmployeeSms;
use App\Jobs\SendCustomerSms;
use App\Jobs\SendEmployeeSms;
use App\Models\AdminSms;
use App\Models\GymClient;
use App\Models\GymSetting;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class GymAdminSMSController extends GymAdminBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['title']      = "SMS";
        $this->data['smsSetting'] = GymSetting::select('detail_id', 'sms_status')->where('detail_id', $this->data['user']->detail_id)->get();
        $this->data['credit_balance'] = getSMSCreditBalance($this->data['user']->detail_id);
        return view('gym-admin.sms.detail', $this->data);
    }

    public function create()
    {
        $this->data['customers'] = GymClient::join('business_customers',
            'business_customers.customer_id', '=', 'gym_clients.id')
            ->where('gym_clients.is_client','yes')
            ->where('business_customers.detail_id', '=', $this->data['user']->detail_id)
            ->select('gym_clients.id', 'gym_clients.first_name', 'gym_clients.middle_name', 'gym_clients.last_name')
            ->get();

        $this->data['active_customers'] = GymClient::getActiveClient($this->data['user']->detail_id);

        $activeIds = $this->data['active_customers']->pluck('customer_id')->toArray();
        $this->data['inactive_customers'] = GymClient::join('business_customers',
            'business_customers.customer_id', '=', 'gym_clients.id')
            ->where('gym_clients.is_client','yes')
            ->where('business_customers.detail_id', '=', $this->data['user']->detail_id)
            ->whereNotIn('customer_id',$activeIds)
            ->select('gym_clients.id', 'gym_clients.first_name', 'gym_clients.middle_name', 'gym_clients.last_name')
            ->get();

        $this->data['employees'] = Employ::where('detail_id', '=', $this->data['user']->detail_id)
            ->select('id', 'first_name', 'middle_name', 'last_name')
            ->get();

        $this->data['admins'] = Merchant::where('user_type', '=','branch_admin')
            ->select('id', 'first_name', 'middle_name', 'last_name')
            ->get();
        return view('gym-admin.sms.create', $this->data);
    }

    public function store(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'message'   => 'required|string',
            'recipient' => 'required',
            'total_messages' => 'required',
        ]);
        if($validator->fails() ) {
            return Reply::formErrors($validator);
        }
        $user = $this->data['user'];

        // Get SMS credit balance
        $creditBalance = getSMSCreditBalance($this->data['user']->detail_id);

        $total_messages = $request->get('total_messages');

        // Count recipients based on type
        $recipientCount = 0;
        switch ($request->get('recipient')) {
            case 'admins':
                $recipientCount = Merchant::where('user_type', '=','branch_admin')->count();
                break;
            case 'employees':
                $recipientCount = Employ::where('detail_id', '=', $this->data['user']->detail_id)->count();
                break;
            case 'customers':
                $recipientCount = GymClient::join('business_customers',
                    'business_customers.customer_id', '=', 'gym_clients.id')
                    ->where('gym_clients.is_client','yes')
                    ->where('business_customers.detail_id', '=', $this->data['user']->detail_id)
                    ->count();
                break;
            case 'active_customers':
                $recipientCount = GymClient::getActiveClient($this->data['user']->detail_id)->count();
                break;
            case 'inactive_customers':
                $activeCustomers = GymClient::getActiveClient($this->data['user']->detail_id);
                $activeIds = $activeCustomers->pluck('customer_id')->toArray();
                $recipientCount = GymClient::join('business_customers',
                    'business_customers.customer_id', '=', 'gym_clients.id')
                    ->where('gym_clients.is_client','yes')
                    ->where('business_customers.detail_id', '=', $this->data['user']->detail_id)
                    ->whereNotIn('customer_id',$activeIds)
                    ->count();
                break;
            default:
                // For individual recipients, count is 1
                $recipientCount = 1;
                break;
        }

        // Check if credit balance is sufficient
        if ((int)$creditBalance < ((int)$recipientCount * (int)$total_messages) ) {
            $total_required = $recipientCount*$total_messages;
            return Reply::error("Insufficient SMS credit. Available: {$creditBalance}, Total receipients: {$recipientCount}, Message page: {$total_messages}, Credit Required: {$total_required}");
        }

        switch ($request->get('recipient')) {
            case 'admins':
                $chunk_data = [];
                $admins = Merchant::where('user_type', '=','branch_admin')->select('id', 'mobile')
                    ->get();
                foreach($admins as $admin){
                    $chunk_data[] = [
                        'message' => $request->get('message'),
                        'status' => 0,
                        'phone' => $admin->mobile,
                        'recipient_id' => $admin->id,
                        'sender_id' => $this->data['user']->id,
                    ];
                }
                $chunks = array_chunk($chunk_data,100);
                foreach ($chunks as $chunk){
                    AdminSms::insert($chunk);
                }
                $sms = AdminSms::where('status',0)->where('sender_id',$this->data['user']->id)->cursor();
                $sms->chunk(100)->each(function ($sms) {
                    BulkAdminSms::dispatch($sms)
                        ->delay(now()->addSeconds(5))->onQueue('bulk_admin_sms');
                });
                break;

            case 'employees':
                $chunk_data = [];
                $employees = Employ::where('detail_id', '=', $this->data['user']->detail_id)
                    ->select('id', 'mobile')
                    ->get();
                foreach($employees as $employee){
                    $chunk_data[] = [
                        'message' => $request->get('message'),
                        'status' => 0,
                        'phone' => $employee->mobile,
                        'recipient_id' => $employee->id,
                        'sender_id' => $this->data['user']->id,
                    ];
                }
                $chunks = array_chunk($chunk_data,100);
                foreach ($chunks as $chunk){
                    EmployeeSms::insert($chunk);
                }
                $sms = EmployeeSms::where('status',0)->where('sender_id',$this->data['user']->id)->cursor();
                $sms->chunk(100)->each(function ($sms) {
                    BulkEmployeeSms::dispatch($sms)
                        ->delay(now()->addSeconds(5))->onQueue('bulk_employee_sms');
                });
                break;
            case 'customers':
                $chunk_data = [];
                $customers = GymClient::join('business_customers',
                    'business_customers.customer_id', '=', 'gym_clients.id')
                    ->where('gym_clients.is_client','yes')
                    ->where('business_customers.detail_id', '=', $this->data['user']->detail_id)
                    ->select('gym_clients.id', 'gym_clients.mobile')->cursor();
                    foreach($customers as $customer){
                        $chunk_data[] = [
                            'message' => $request->get('message'),
                            'status' => 0,
                            'phone' => $customer->mobile,
                            'recipient_id' => $customer->id,
                            'sender_id' => $this->data['user']->id,
                        ];
                    }
                    $chunks = array_chunk($chunk_data,100);
                    foreach ($chunks as $chunk){
                        CustomerSms::insert($chunk);
                    }
                    $sms = CustomerSms::where('status',0)->where('sender_id',$this->data['user']->id)->get();
                    $sms->chunk(100)->each(function ($sms) {
                        BulkCustomerSms::dispatch($sms)
                            ->delay(now()->addSeconds(5))->onQueue('bulk_customer_sms');
                    });

                break;
            case 'active_customers':
                $chunk_data = [];
                $customers = GymClient::getActiveClient($this->data['user']->detail_id);
                    foreach($customers as $customer){
                        $chunk_data[] = [
                            'message' => $request->get('message'),
                            'status' => 0,
                            'phone' => $customer->mobile,
                            'recipient_id' => $customer->id,
                            'sender_id' => $this->data['user']->id,
                        ];
                    }
                    $chunks = array_chunk($chunk_data,100);
                    foreach ($chunks as $chunk){
                        CustomerSms::insert($chunk);
                    }
                    $sms = CustomerSms::where('status',0)->where('sender_id',$this->data['user']->id)->get();
                    $sms->chunk(100)->each(function ($sms) {
                        BulkCustomerSms::dispatch($sms)
                            ->delay(now()->addSeconds(5))->onQueue('bulk_customer_sms');
                    });

                break;
            case 'inactive_customers':
                $chunk_data = [];
                $activeCustomers = GymClient::getActiveClient($this->data['user']->detail_id);
                $activeIds = $activeCustomers->pluck('customer_id')->toArray();
                $customers = GymClient::join('business_customers',
                    'business_customers.customer_id', '=', 'gym_clients.id')
                    ->where('gym_clients.is_client','yes')
                    ->where('business_customers.detail_id', '=', $this->data['user']->detail_id)
                    ->whereNotIn('customer_id',$activeIds)
                    ->select('gym_clients.id', 'gym_clients.mobile')
                    ->cursor();
                    foreach($customers as $customer){
                        $chunk_data[] = [
                            'message' => $request->get('message'),
                            'status' => 0,
                            'phone' => $customer->mobile,
                            'recipient_id' => $customer->id,
                            'sender_id' => $this->data['user']->id,
                        ];
                    }
                    $chunks = array_chunk($chunk_data,100);
                    foreach ($chunks as $chunk){
                        CustomerSms::insert($chunk);
                    }
                    $sms = CustomerSms::where('status',0)->where('sender_id',$this->data['user']->id)->get();
                    $sms->chunk(100)->each(function ($sms) {
                        BulkCustomerSms::dispatch($sms)
                            ->delay(now()->addSeconds(5))->onQueue('bulk_customer_sms');
                    });

                break;
                default:
                    $result = $request->get('recipient');
                    $result_explode = explode('|', $result);
                    if ($result_explode[0] === 'customer') {
                        $customer = GymClient::join('business_customers',
                            'business_customers.customer_id', '=', 'gym_clients.id')
                            ->where('gym_clients.is_client','yes')
                            ->where('business_customers.detail_id', '=', $this->data['user']->detail_id)
                            ->select('gym_clients.id', 'gym_clients.mobile')
                            ->findOrFail($result_explode[1]);
                        if (isset($customer->mobile)) {
                            $sms = new CustomerSms(array(
                                'message' => $request->message,
                                'status' => 0,
                                'phone' => $customer->mobile
                            ));
                            $sms->recipient()->associate($customer);
                            $sms->sender()->associate($user);
                            $sms->save();
                            $job = new SendCustomerSms($sms);
                            $this->dispatch($job);
                        }
                    } elseif ($result_explode[0] === 'employee') {
                        $employee = Employ::where('detail_id', '=', $this->data['user']->detail_id)
                            ->select('id', 'mobile')
                            ->findOrFail($result_explode[1]);
                        if (isset($employee->mobile)) {
                            $sms = new EmployeeSms(array(
                                'message' => $request->get('message'),
                                'status' => 0,
                                'phone' => $employee->mobile
                            ));
                            $sms->recipient()->associate($employee);
                            $sms->sender()->associate($user);
                            $sms->save();
                            $job = new SendEmployeeSms($sms);
                            $this->dispatch($job);
                        }
                    }else if ($result_explode[0] === 'admin') {
                        $customer = Merchant::where('user_type','=','branch_admin')
                            ->where('id', '=', $result_explode[1])
                            ->select('id', 'mobile')->get();
                        if (isset($customer->mobile)) {
                            $sms = new AdminSms(array(
                                'message' => $request->message,
                                'status' => 0,
                                'phone' => $customer->mobile
                            ));
                            $sms->recipient()->associate($customer);
                            $sms->sender()->associate($user);
                            $sms->save();
                            $job = new SendAdminSms($sms);
                            $this->dispatch($job);
                        }
                    }

        }
        return Reply::success('SMS is being sent on queue.');
    }

    public function resendSms(Request $request)
    {
        switch ($request->get('type')) {
            case 'admin':
                $sms = AdminSms::find($request->get('id'));
                $resend_sms = AdminSms::create([
                    'message' => $sms->message,
                    'status' => 0,
                    'phone' => $sms->phone,
                    'recipient_id' => $sms->recipient_id,
                    'sender_id' => $sms->sender_id
                ]);
                $job = new SendAdminSms($resend_sms);
                $this->dispatch($job);
                break;
            case 'employee':
                $sms = EmployeeSms::find($request->get('id'));
                $resend_sms = EmployeeSms::create([
                    'message' => $sms->message,
                    'status' => 0,
                    'phone' => $sms->phone,
                    'recipient_id' => $sms->recipient_id,
                    'sender_id' => $sms->sender_id
                ]);
                $job = new SendEmployeeSms($resend_sms);
                $this->dispatch($job);
                break;
            case 'customer':
                $sms = CustomerSms::find($request->get('id'));
                $resend_sms = CustomerSms::create([
                    'message' => $sms->message,
                    'status' => 0,
                    'phone' => $sms->phone,
                    'recipient_id' => $sms->recipient_id,
                    'sender_id' => $sms->sender_id
                ]);
                $job = new SendCustomerSms($resend_sms);
                $this->dispatch($job);
                break;

        }
        return Reply::success('SMS is being sent.');
    }

    public function showCustomer()
    {
        $this->data['title']      = 'customer';
        $this->data['smsSetting'] = GymSetting::select('detail_id', 'sms_status')->where('detail_id', $this->data['user']->detail_id)->get();
        return view('gym-admin.sms.show', $this->data);
    }

    public function ajaxCustomerSms(Request $request)
    {
        $query = CustomerSms::select('customer_sms.id','customer_sms.phone', 'customer_sms.message', 'customer_sms.status', 'gym_clients.first_name', 'gym_clients.middle_name', 'gym_clients.last_name','customer_sms.created_at')
            ->leftJoin('gym_clients', 'customer_sms.recipient_id', '=', 'gym_clients.id')
            ->join('business_customers', 'business_customers.customer_id', '=', 'gym_clients.id')
            ->where('business_customers.detail_id', '=', $this->data['user']->detail_id)
            ->latest();

        return Datatables::of($query)
            ->editColumn('gym_clients.first_name', function ($row) {
                $name = ucwords($row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name);
                return $name;
            })
            ->addColumn('action', function ($row) {
                $tableId = 'customer';
                if($this->data['gymSettings']->sms_status === 'enabled'){
                    return '<div class="btn-group">' .
                    '<button class="btn blue btn-xs dropdown-toggle" type="button" data-toggle="dropdown">' .
                    '<i class="fa fa-gears"></i> <span class="hidden-xs">Actions</span>' .
                    '<i class="fa fa-angle-down"></i>' .
                    '</button>' .
                    '<ul class="dropdown-menu pull-right" role="menu">' .
                    '<li><a href="javascript:;" data-id="' . $row->id . '" class="remove-message"><i class="fa fa-trash"></i> Remove</a></li>' .
                    '<li><a href="javascript:;" data-id="' . $row->id . '" data-type="' . $tableId . '" class="resend-message"><i class="fa fa-repeat"></i> Resend</a></li>' .
                    '</ul>' .
                    '</div>';
                }
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->toFormattedDateString();
            })
            ->editColumn('status', function ($row) {
                return $row->status == 1 ? 'sent' : 'error';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function showAdmin()
    {
        $this->data['title'] = 'admin';
        $this->data['smsSetting'] = GymSetting::select('detail_id', 'sms_status')->where('id', 1)->get();
        return view('gym-admin.sms.show', $this->data);
    }

    public function ajaxAdminSms(Request $request)
    {
        $query = AdminSms::select('admin_sms.id','admin_sms.phone', 'admin_sms.message', 'admin_sms.status', 'merchants.first_name', 'merchants.middle_name', 'merchants.last_name','admin_sms.created_at')
            ->leftJoin('merchants', 'admin_sms.recipient_id', '=', 'merchants.id')
            ->latest();

        return Datatables::of($query)
            ->editColumn('merchants.first_name', function ($row) {
                $name = ucwords($row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name);
                return $name;
            })
            ->addColumn('action', function ($row) {
                $tableId = 'admin';
                return '<div class="btn-group">' .
                    '<button class="btn blue btn-xs dropdown-toggle" type="button" data-toggle="dropdown">' .
                    '<i class="fa fa-gears"></i> <span class="hidden-xs">Actions</span>' .
                    '<i class="fa fa-angle-down"></i>' .
                    '</button>' .
                    '<ul class="dropdown-menu pull-right" role="menu">' .
                    '<li><a href="javascript:;" data-id="' . $row->id . '" class="remove-message"><i class="fa fa-trash"></i> Remove</a></li>' .
                    '<li><a href="javascript:;" data-id="' . $row->id . '" data-type="' . $tableId . '" class="resend-message"><i class="fa fa-repeat"></i> Resend</a></li>' .
                    '</ul>' .
                    '</div>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->toFormattedDateString();
            })
            ->editColumn('status', function ($row) {
                return $row->status == 1 ? 'sent' : 'error';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function showEmployee()
    {
        $this->data['title']      = 'employee';
        $this->data['smsSetting'] = GymSetting::select('detail_id', 'sms_status')->where('detail_id', $this->data['user']->detail_id)->get();
        return view('gym-admin.sms.show', $this->data);
    }

    public function ajaxEmployeeSms(Request $request)
    {
        $query = EmployeeSms::select('employee_sms.id','employee_sms.phone', 'employee_sms.message', 'employee_sms.status', 'employes.first_name', 'employes.middle_name', 'employes.last_name','employee_sms.created_at')
            ->leftJoin('employes', 'employee_sms.recipient_id', '=', 'employes.id')
            ->where('employes.detail_id', '=', $this->data['user']->detail_id)
            ->latest();

        return Datatables::of($query)
            ->editColumn('employes.first_name', function ($row) {
                $name = ucwords($row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name);
                return $name;
            })
            ->addColumn('action', function ($row) {
                $tableId = 'employee';
                if($this->data['gymSettings']->sms_status === 'enabled'){
                    return '<div class="btn-group">' .
                    '<button class="btn blue btn-xs dropdown-toggle" type="button" data-toggle="dropdown">' .
                    '<i class="fa fa-gears"></i> <span class="hidden-xs">Actions</span>' .
                    '<i class="fa fa-angle-down"></i>' .
                    '</button>' .
                    '<ul class="dropdown-menu pull-right" role="menu">' .
                    '<li><a href="javascript:;" data-id="' . $row->id . '" class="remove-message"><i class="fa fa-trash"></i> Remove</a></li>' .
                    '<li><a href="javascript:;" data-id="' . $row->id . '" data-type="' . $tableId . '" class="resend-message"><i class="fa fa-repeat"></i> Resend</a></li>' .
                    '</ul>' .
                    '</div>';
                }

            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->toFormattedDateString();
            })
            ->editColumn('status', function ($row) {
                return $row->status == 1 ? 'sent' : 'error';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroyCustomerSms($id, Request $request)
    {
        if ($request->ajax()) {
            CustomerSms::find($id)->delete();
            return Reply::success('SMS removed successfully');
        }
        return Reply::error('Request not Valid');
    }

    public function destroyEmployeeSms($id, Request $request)
    {
        if ($request->ajax()) {
            EmployeeSms::find($id)->delete();
            return Reply::success('SMS removed successfully');
        }
        return Reply::error('Request not Valid');
    }

    public function destroyAdminSms($id, Request $request)
    {
        if ($request->ajax()) {
            AdminSms::find($id)->delete();
            return Reply::success('SMS removed successfully');
        }
        return Reply::error('Request not Valid');
    }

    public function destoryMassCustomerSms(Request $request)
    {
        $customer_id_array = $request->input('id');
        $entries = CustomerSms::whereIn('id', $customer_id_array)->get();
        foreach ($entries as $entry) {
            $entry->delete();
        }
        return response()->json(['success'=>'Sms removed successfully.']);
    }

    public function destoryMassAdminSms(Request $request)
    {
        $customer_id_array = $request->input('id');
        $entries = AdminSms::whereIn('id', $customer_id_array)->get();
        foreach ($entries as $entry) {
            $entry->delete();
        }
        return response()->json(['success'=>'Sms removed successfully.']);
    }

    public function destoryMassEmployeeSms(Request $request)
    {
        $customer_id_array = $request->input('id');
        $entries = EmployeeSms::whereIn('id', $customer_id_array)->get();
        foreach ($entries as $entry) {
            $entry->delete();
        }
        return response()->json(['success'=>'Sms removed successfully.']);
    }
}

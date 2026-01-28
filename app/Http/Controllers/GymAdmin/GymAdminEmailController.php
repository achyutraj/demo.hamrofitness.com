<?php

    namespace App\Http\Controllers\GymAdmin;

    use App\Classes\Reply;
    use App\Models\CustomerEmail;
    use App\Models\Email;
    use App\Models\Employ;
    use App\Models\EmployeeEmail;
    use App\Http\Requests\GymAdmin\Communication\SendEmailRequest;
    use App\Jobs\SendCustomerEmail;
    use App\Jobs\SendEmail;
    use App\Jobs\SendEmployeeEmail;
    use App\Models\GymClient;
    use App\Models\GymSetting;
    use App\Models\Merchant;
    use Illuminate\Http\Request;
    use Yajra\Datatables\Datatables;

    class GymAdminEmailController extends GymAdminBaseController
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function index()
        {
            $this->data['title']        = "Email";
            $this->data['emailSetting'] = GymSetting::select('email_status')->where('detail_id', $this->data['user']->detail_id)->get();
            return view('gym-admin.emails.detail', $this->data);
        }


        public function create()
        {
            $this->data['customers'] = GymClient::join('business_customers',
                'business_customers.customer_id', '=', 'gym_clients.id')
                ->where('business_customers.detail_id', '=', $this->data['user']->detail_id)
                ->select('gym_clients.id', 'gym_clients.first_name', 'gym_clients.middle_name', 'gym_clients.last_name')
                ->get();
            $this->data['employees'] = Employ::where('detail_id', '=', $this->data['user']->detail_id)
                ->select('id', 'first_name', 'middle_name', 'last_name')
                ->get();

            return view('gym-admin.emails.create', $this->data);
        }

        public function store(SendEmailRequest $request)
        {
            $this->validate($request, array(
                'subject'   => 'required|string',
                'message'   => 'required|string',
                'recipient' => 'required',
            ));
            $user = $this->data['user'];
            $user_id = $this->data['user']->id;
            $res = GymSetting::where('detail_id', $user_id)->get();
            if ($res[0]->email_status === 'disabled') {
                return Reply::error('Email Not Sent, Enabled your Smtp credentials');
            }
            switch ($request->recipient) {
                case 'admins':
                    foreach (Merchant::admin()->get()->chunk(100) as $admin) {
                        $email = new Email(array(
                            'message' => $request->message,
                            'status'  => 0,
                            'subject' => $request->subject
                        ));
                        $email->recipient()->associate($admin);
                        $email->sender()->associate($user);
                        $email->save();
                        try {
                            $job = new SendEmail($email, 'gym-admin.emails.template');
                        } catch (\Exception $e) {
                            return Reply::error('Email Not Sent, check your Smtp credentials');
                        }
                        $this->dispatch($job->onQueue('admin emails'));
                    }
                    break;
                case 'employees':
                    $employees = Employ::where('detail_id', '=', $this->data['user']->detail_id)
                        ->select('id', 'mobile')
                        ->get();
                    foreach ($employees->chunk(100) as $employee) {
                        $email = new EmployeeEmail(array(
                            'subject' => $request->subject,
                            'message' => $request->message,
                            'status'  => 0,
                        ));
                        $email->recipient()->associate($employee);
                        $email->sender()->associate($user);
                        $email->save();
                        try{
                                $job = new SendEmployeeEmail($email, 'gym-admin.emails.template');
                            } catch (\Exception $e) {
                            return Reply::error('Email Not Sent, check your Smtp credentials');
                        }
                        $this->dispatch($job->onQueue('employee emails'));
                    }
                    break;
                case 'customers':
                    $customers = GymClient::join('business_customers',
                        'business_customers.customer_id', '=', 'gym_clients.id')
                        ->where('business_customers.detail_id', '=', $this->data['user']->detail_id)
                        ->select('gym_clients.id', 'gym_clients.mobile')
                        ->get();

                    foreach ($customers->chunk(100) as $customer) {
                        $email = new CustomerEmail(array(
                            'message' => $request->message,
                            'status'  => 0,
                            'subject' => $request->subject
                        ));
                        $email->recipient()->associate($customer);
                        $email->sender()->associate($user);
                        $email->save();
                        try{
                            $job = new SendCustomerEmail($email, 'gym-admin.emails.template');
                        }catch (\Exception $e) {
                            return Reply::error('Email Not Sent, check your Smtp credentials');
                        }
                        $this->dispatch($job->onQueue('customer emails'));
                    }
                    break;
                default:
                    $result         = $request->recipient;
                    $result_explode = explode('|', $result);
                    if ($result_explode[0] === 'customer') {
                        $customer = GymClient::join('business_customers',
                            'business_customers.customer_id', '=', 'gym_clients.id')
                            ->where('business_customers.detail_id', '=', $this->data['user']->detail_id)
                            ->select('gym_clients.id', 'gym_clients.mobile')
                            ->findOrFail($result_explode[1]);
                        $email    = new CustomerEmail(array(
                            'message' => $request->message,
                            'status'  => 0,
                            'subject' => $request->subject
                        ));
                        $email->recipient()->associate($customer);
                        $email->sender()->associate($user);
                        $email->save();
                        try{
                            $job = new SendCustomerEmail($email, 'gym-admin.emails.template');
                        }catch (\Exception $e) {
                            return Reply::error('Email Not Sent, check your Smtp credentials');
                        }
                        $this->dispatch($job->onQueue('customer emails'));
                    } elseif ($result_explode[0] === 'employee') {
                        $employee = Employ::where('detail_id', '=', $this->data['user']->detail_id)
                            ->select('id', 'mobile')
                            ->findOrFail($result_explode[1]);
                        $email    = new EmployeeEmail(array(
                            'subject' => $request->subject,
                            'message' => $request->message,
                            'status'  => 0,
                        ));
                        $email->recipient()->associate($employee);
                        $email->sender()->associate($user);
                        $email->save();
                        try{
                            $job = new SendEmployeeEmail($email, 'gym-admin.emails.template');
                        }catch (\Exception $e) {
                            return Reply::error('Email Not Sent, check your Smtp credentials');
                        }
                        $this->dispatch($job->onQueue('employee emails'));
                    }
            }
            return Reply::success('Email is being sent.');
        }

        public function showCustomer(Request $request)
        {
            $this->data['emailSetting']    = GymSetting::select('email_status')->where('detail_id', $this->data['user']->detail_id)->get();
            $this->data['title']           = 'Customers';
            $this->data['customer_emails'] = CustomerEmail::select('customer_emails.id', 'customer_emails.message', 'customer_emails.subject', 'customer_emails.status', 'gym_clients.first_name', 'gym_clients.last_name')
                ->leftJoin('gym_clients', 'customer_emails.recipient_id', '=', 'gym_clients.id')
                ->join('business_customers', 'business_customers.customer_id', '=', 'gym_clients.id')
                ->where('business_customers.detail_id', '=', $this->data['user']->detail_id)->get();
            return view('gym-admin.emails.show', $this->data);
        }


        public function showAdmin(Request $request)
        {
            $this->data['title'] = 'Admins';
            return view('gym-admin.emails.show', $this->data);
        }

        public function showEmployee(Request $request)
        {
            $this->data['emailSetting']    = GymSetting::select('email_status')->where('detail_id', $this->data['user']->detail_id)->get();
            $this->data['title']           = 'Employees';
            $this->data['employee_emails'] = EmployeeEmail::select('employee_emails.id', 'employee_emails.subject', 'employee_emails.message', 'employee_emails.status', 'employes.first_name', 'employes.last_name')
                ->leftJoin('employes', 'employee_emails.recipient_id', '=', 'employes.id')
                ->where('employes.detail_id', '=', $this->data['user']->detail_id)->get();
            return view('gym-admin.emails.show', $this->data);
        }

        public function edit($id)
        {
            //
        }

        public function update(Request $request, $id)
        {
            //
        }

        public function destroy($id)
        {
            //
        }

        public function destoryMassCustomerEmail(Request $request)
        {
            $customer_id_array = $request->input('id');
            $entries = CustomerEmail::whereIn('id', $customer_id_array)->get();
            foreach ($entries as $entry) {
                $entry->delete();
            }
            return response()->json(['success'=>'Email removed successfully.']);
        }
        public function destoryMassEmployeeEmail(Request $request)
        {
            $customer_id_array = $request->input('id');
            $entries = EmployeeEmail::whereIn('id', $customer_id_array)->get();
            foreach ($entries as $entry) {
                $entry->delete();
            }
            return response()->json(['success'=>'Email removed successfully.']);
        }

        public function destroyCustomerEmail($id, Request $request)
        {
            if ($request->ajax()) {
                CustomerEmail::find($id)->delete();
                return Reply::success('Email removed successfully');
            }

            return Reply::error('Request not Valid');
        }

        public function destroyEmployeeEmail($id, Request $request)
        {
            if ($request->ajax()) {
                EmployeeEmail::find($id)->delete();
                return Reply::success('Email removed successfully');
            }

            return Reply::error('Request not Valid');
        }
    }

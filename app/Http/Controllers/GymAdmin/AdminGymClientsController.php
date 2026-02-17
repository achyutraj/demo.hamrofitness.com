<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Http\Requests\GymAdmin\Image\ImageRequest;
use App\Jobs\SendCustomerSms;
use App\Models\BusinessCustomer;
use App\Models\CustomerSms;
use App\Models\GymClient;
use App\Models\GymClientAttendance;
use App\Models\GymEnquiries;
use App\Models\GymMembershipPayment;
use App\Models\GymPurchase;
use App\Models\LockerPayment;
use App\Models\LockerReservation;
use App\Models\Product;
use App\Models\ProductPayment;
use App\Models\ProductSales;
use App\Models\Template;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use DataTables;
use PDF;

class AdminGymClientsController extends GymAdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->data['manageMenu'] = 'active';
        $this->data['customerMenu'] = 'active';
    }

    public function index()
    {
        if (!$this->data['user']->can("view_customers")) {
            return App::abort(401);
        }
        $this->data['title'] = "Clients";
        return View::make('gym-admin.gymclients.index', $this->data);
    }

    public function index2()
    {
        if (!$this->data['user']->can("view_customers")) {
            return App::abort(401);
        }
        $this->data['title'] = "Clients";
        $this->data['clients'] = GymClient::GetClients($this->data['user']->detail_id)->with('activeMembership')->get();
        return View::make('gym-admin.gymclients.index2', $this->data);
    }

    public function ajax_create()
    {
        if (!$this->data['user']->can("view_customers")) {
            return App::abort(401);
        }

        $gym_clients = GymClient::GetClients($this->data['user']->detail_id)->with('activeMembership');
        return Datatables::of($gym_clients)
            ->addIndexColumn()
            ->editColumn('first_name', function ($row) {
                if ($row->image != '') {
                    $image_url =  $this->data['profileHeaderPath'] . $row->image ;
                } else {
                    $image_url =  asset('/fitsigma/images/user.svg');
                }
                return '<a href="'. route('gym-admin.client.show', $row->id).'"><img style="width:50px;height:50px;" class="img-circle" src="' . $image_url. '" alt="" /><br>'.
                        $row->fullName .'<br> <i class="fa fa-envelope"> '.$row->email.'</a>' ;

            })
            ->editColumn('referred_client_id', function ($row) {
                return $row->referred_by ? $row->referred_by->fullName : '';
            })
            ->editColumn('mobile', function ($row) {
                $contact = $row->mobile;
                if (!empty($row->emergency_contact)) {
                    $contact .= '<p>(Emergency NO. ' . $row->emergency_contact . ') </p>';
                }
                return $contact;
            })
            ->editColumn('joining_date', function ($row) {
                if (!is_null($row->joining_date))
                    return $row->joining_date->toFormattedDateString();
            })
            ->addColumn('action', function ($row) {
                $point = getActiveRedeemPoint($this->data['user']->detail_id);
                $action = '<div class="btn-group">
                    <button class="btn blue btn-xs dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-gears"></i> <span class="hidden-xs hidden-medium">Actions</span>
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <li>
                            <a href="' . route('gym-admin.client.show', $row->id) . '"> <i class="fa fa-edit"></i>Show Profile</a>
                        </li>';

                if($row->status == 1){
                    $action .='<li>
                    <a href="' . route('gym-admin.client-purchase.user-create', $row->id) . '"> <i class="fa fa-plus"></i>Add Membership</a>
                    </li>';
                }
                if($row->status == 1 && !is_null($point) && $row->redeem_points >= $point->redeem_points){
                    $action .='<li>
                    <a href="' . route('gym-admin.client-purchase.user-create', ['id'=>$row->id,'redeem_mem'=>$point->membership_id]) . '"> <i class="fa fa-gift"></i>Redeem Offer</a>
                    </li>';
                }

                if($row->subscriptions()->count() == 0){
                    $action .='<li>
                        <a href="javascript:;" onClick="deleteModal(' . $row->id . ')"><i class="fa fa-trash"></i> Delete</a>
                    </li>';
                }

                $action .='
                        <li>
                            <a href="' . route('gym-admin.client.calender', $row->id) . '" > <i class="fa fa-calendar"></i> View Attendance</a>
                        </li>
                    </ul>
                </div>';
                return $action;
            })
            ->addColumn('membership', function ($row) {
                if($row->activeMembership != null){
                    $data = $row->activeMembership->membership?->title .'<br>';
                    if($row->activeMembership->expires_on !== null){
                        if($row->activeMembership->expires_on > today()){
                            if($row->status == 1){
                                $data .= '<label class="label label-success"> Active </label>';
                            }else{
                                $data .= '<label class="label label-danger"> Inactive User</label>';
                            }
                        }else{
                            if($row->status == 1){
                                $data .= '<label class="label label-danger"> Expired </label>';
                            }else{
                                $data .= '<label class="label label-danger">Expired & Inactive User</label>';
                            }
                        }
                    }

                    return $data;
                }else{
                    return '<label class="label label-danger"> No Subscription</label>';
                }
            })
            ->addColumn('locker', function ($row) {
                if($row->activeLockerReservation != null){
                    $data = $row->activeLockerReservation->locker?->locker_num .'<br>';
                    if($row->activeLockerReservation->end_date !== null){
                        if($row->activeLockerReservation->end_date > today()){
                            if($row->status == 1){
                                $data .= '<label class="label label-success"> Active </label>';
                            }else{
                                $data .= '<label class="label label-danger"> Inactive User</label>';
                            }
                        }else{
                            if($row->status == 1){
                                $data .= '<label class="label label-danger"> Expired </label>';
                            }else{
                                $data .= '<label class="label label-danger">Expired & Inactive User</label>';
                            }
                        }
                    }

                    return $data;
                }else{
                    return '<label class="label label-danger"> No Reservation</label>';
                }
            })
            ->rawColumns(['action', 'first_name', 'mobile','membership','locker','referred_client_id'])
            ->make(true);
    }

    public function create()
    {
        if (!$this->data['user']->can("add_customers")) {
            return App::abort(401);
        }
        $this->data['title'] = "Clients Create";
        $this->data['clients'] = GymClient::GetClients($this->data['user']->detail_id)->get();
        return View::make('gym-admin.gymclients.create', $this->data);
    }

    public function store(Request $request)
    {
        if (!$this->data['user']->can("add_customers")) {
            return App::abort(401);
        }

        $validate = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'gender' => 'required',
            'marital_status' => 'required',
            'emergency_contact' => 'nullable|digits:10',
            'blood_group' => 'nullable',
            'email' => 'required|email|unique:gym_clients,email',
            'mobile' => 'required|digits:10'
        ]);
        if ($validate->fails()) {
            $errors = $validate->errors();
            return redirect()->back()->with('errors', $errors)->withInput();
        }
        if (!empty($request->get('id'))) {
            $id = $request->get('id');
            $data = GymEnquiries::find($id);
            $data->is_client = 1;
            $data->save();
        }
        $inputData = $request->all();
        $inputData['password'] = Hash::make($request->get('first_name').'123456');

        if ($request->get('dob') != '') {
            $inputData['dob'] = Carbon::createFromFormat('m/d/Y', $request->get('dob'))->format('Y-m-d');
        }
        if ($request->get('marital_status') == 'yes' && $request->get('anniversary') != '') {
            $inputData['anniversary'] = Carbon::createFromFormat('m/d/Y', trim($request->get('anniversary')))->format('Y-m-d');
        }
        if ($request->get('marital_status') == 'yes' && $request->get('anniversary') != '') {
            $inputData['anniversary'] = Carbon::createFromFormat('m/d/Y', $request->get('anniversary'))->format('Y-m-d');
        }

        $inputData['joining_date'] = Carbon::today()->format('Y-m-d');
        $inputData['referred_client_id'] = $request->get('referred_by');
        $full_name = $request->get('first_name');
        if($request->get('middle_name') != null){
            $full_name .=' '. $request->get('middle_name');
        }
        $full_name .= ' '.$request->get('last_name');
        $inputData['username'] = Str::slug($full_name);
        $gymClient = GymClient::create($inputData);
        $businessCustomer = new BusinessCustomer();
        $businessCustomer->detail_id = $this->data['merchantBusiness']->detail_id;
        $businessCustomer->customer_id = $gymClient->id;
        $businessCustomer->save();

        if($this->data['gymSettings']->sms_status == 'enabled') {
            if($this->data['options']['customer_register_status'] == 1 && $this->data['options']['customer_register_notify'] == 'sms'){
                $this->sendSms($gymClient->id);
            }
        }

        return redirect()->to(route('gym-admin.client-purchase.user-create',$gymClient->id))->with('message', 'Client Added Successfully');
    }

    public function downloadProfile($id)
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $client = GymClient::findBusinessClient($this->data['user']->detail_id,$id)->first();
        if (!isset($client)) {
            return redirect()->route('gym-admin.client.index');
        }
        $this->data['client'] = $client;
        $this->data['age'] = (!is_null($client->dob)) ? $client->dob->diff(Carbon::now())->format('%y') : '-';
        $this->data['title'] = "Client Profile";

        $this->data['memberships'] = GymPurchase::whereBetween('created_at', [$startOfMonth, $endOfMonth])->where('client_id',  $id)->get();

        $this->data['payments'] = GymMembershipPayment::whereBetween('created_at', [$startOfMonth, $endOfMonth])->where('user_id', $id)->get();

        $this->data['dues'] = GymPurchase::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                            ->where('payment_required','yes')
                            ->where('client_id', $id)->get();

        $this->data['productPayments'] = ProductPayment::whereBetween('created_at', [$startOfMonth, $endOfMonth])->where('user_id', $id)->get();

        $this->data['productDues'] = ProductSales::whereBetween('created_at', [$startOfMonth, $endOfMonth])->where('client_id', $id)->get();

        $this->data['reservations'] = LockerReservation::whereBetween('created_at', [$startOfMonth, $endOfMonth])->where('client_id',  $id)->get();

        $this->data['locker_payments'] = LockerPayment::whereBetween('created_at', [$startOfMonth, $endOfMonth])->where('client_id', $id)->get();

        $this->data['locker_dues'] = LockerReservation::whereBetween('created_at', [$startOfMonth, $endOfMonth])->where('client_id', $id)->get();

        $pdf = PDF::loadView('gym-admin.gymclients.profileReport', $this->data);

        $filename = $client->first_name . '-profile.pdf';
        return $pdf->download($filename);
    }

    public function show($id)
    {
        if (!$this->data['user']->can("edit_customers")) {
            return App::abort(401);
        }
        $client = GymClient::findBusinessClient($this->data['user']->detail_id,$id)->first();
        if (!isset($client)) {
            return redirect()->route('gym-admin.client.index')->withErrors(['error', 'Customer not found.']);
        }
        $this->data['title'] = "Clients Update";
        $this->data['client'] = $client;
        $this->data['age'] = (!is_null($client->dob)) ? $client->dob->diff(Carbon::now())->format('%y') : '-';
        $this->data['customers'] = GymClient::GetClients($this->data['user']->detail_id)->where('customer_id','!=',$id)->get();
        $this->data['total_purchases'] = GymPurchase::where(['client_id'=>$id,'detail_id'=>$this->data['user']->detail_id])->count();
        $this->data['total_reservations'] = LockerReservation::where(['client_id'=>$id,'detail_id'=>$this->data['user']->detail_id])->count();
        $this->data['referred_clients'] = GymClient::getClientReferredLists($this->data['user']->detail_id,$id)->get();

        return View::make('gym-admin.gymclients.edit', $this->data);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $this->validate($request, [
            'first_name' => 'required|alpha_spaces',
            'last_name' => 'required|alpha_spaces',
            'gender' => 'required',
            'email' => 'required|email|unique:gym_clients,email,' . $id,
            'mobile' => 'required|digits:10',
            'marital_status' => 'required',
            'blood_group' => 'nullable',
            'emergency_contact' => 'nullable|digits:10|unique:gym_clients,emergency_contact,' . $id,
        ]);

        if (!$this->data['user']->can("edit_customers")) {
            return App::abort(401);
        }
        $id = $request->get('id');

        if ($request->get('type') == 'general') {
            $inputData = $request->all();
            if ($request->get('password')) {
                $inputData['password'] = Hash::make($request->get('password'));
            }
            if ($request->get('dob') != '') {
                $inputData['dob'] = Carbon::createFromFormat('m/d/Y', $request->get('dob'))->format('Y-m-d');
            }
            if ($request->get('marital_status') == 'yes' && $request->get('anniversary') != '') {
                $inputData['anniversary'] = Carbon::createFromFormat('m/d/Y', trim($request->get('anniversary')))->format('Y-m-d');
            }
            $inputData['status'] = $request->get('status');
            $inputData['referred_client_id'] = $request->get('referred_by');
            $gym_client = GymClient::find($id);
            $gym_client->update($inputData);

            return Reply::redirect(route('gym-admin.client.show', $id), "Clients personal information uploaded successfully");
        }
        if ($request->get('type') == 'file') {
            return Reply::redirect(route('gym-admin.client.show', $id), "Clients Image uploaded successfully");
        }
        return Reply::error("Invalid request");
    }

    public function removeClient($id)
    {
        if (!$this->data['user']->can("delete_customers")) {
            return App::abort(401);
        }
        $this->data['client'] = GymClient::select('first_name', 'middle_name', 'last_name', 'id')->where('id', '=', $id)->first();
        return View::make('gym-admin.gymclients.destroy', $this->data);
    }

    public function destroy($id)
    {
        if (!$this->data['user']->can("delete_customers")) {
            return App::abort(401);
        }
        $client = GymClient::findorfail($id);
        if($client->subscriptions->count() > 0 || $client->product_sells->count() > 0 || $client->reservations->count() > 0){
            return redirect(route('gym-admin.client.index'))->with('danger', 'Sorry! , Client has Subscription.');
        }
        $client->devices()->detach();
        $client->shifts()->detach();
        $client->delete();
        $enquiry = GymEnquiries::where('email', '=', $client->email)->first();
        if ($enquiry != null) {
            $data = GymEnquiries::find($enquiry->id);
            $data->delete();
        }
        return redirect(route('gym-admin.client.index'))->with('message', 'Client Deleted Successfully');
    }

    public function calender($id)
    {
        if (!$this->data['user']->can("view_customer_attendance")) {
            return App::abort(401);
        }

        $this->data['title'] = "Calender";
        $this->data['attendance'] = GymClientAttendance::where('client_id', '=', $id)->get();
        $this->data['client'] = GymClient::find($id);
        return View::make('gym-admin.gymclients.calendar', $this->data);
    }

    public function getData($id){
        $this->data['client'] = GymClient::findBusinessClient($this->data['user']->detail_id,$id)->first();
        return $this->data['client'];
    }

    public function ajax_membership_subscriptions($id)
    {
        if (!$this->data['user']->can("edit_customers")) {
            return App::abort(401);
        }

        $purchases = GymPurchase::where(['client_id'=>$id,'detail_id'=>$this->data['user']->detail_id])->get();

        return Datatables::of($purchases)
            ->addColumn('membership', function ($row) {
                $title = ucwords($row->membership?->title) ?? '';
                $data = $title.'<br> Duration: '.$row->membership?->duration.' '.$row->membership?->duration_type;
                return $data;
            })
            ->editColumn('status', function ($row) {
                if($row->membership->duration_type == 'unlimited' || $row->expires_on > Carbon::today()->format('Y-m-d')){
                    return '<span class="label label-success uppercase"> Active </span>';
                }else{
                    return '<span class="label label-danger uppercase"> Expired </span>';
                }
            })
            ->editColumn('amount_to_be_paid', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . $row->amount_to_be_paid;
            })
            ->editColumn('start_date', function ($row) {
                return $row->start_date->toFormattedDateString();
            })
            ->editColumn('expires_on', function ($row) {
                return !is_null($row->expires_on) ? $row->expires_on->toFormattedDateString() : '';
            })
            ->rawColumns(['amount_to_be_paid','start_date','expires_on','status', 'membership'])
            ->make();
    }

    public function ajax_locker_reservations($id)
    {
        if (!$this->data['user']->can("edit_customers")) {
            return App::abort(401);
        }
        $reservations = LockerReservation::where(['client_id'=>$id,'detail_id'=>$this->data['user']->detail_id])->get();
        return Datatables::of($reservations)
            ->addColumn('locker', function ($row) {
                return ucwords($row->locker->locker_num) ?? '';
            })
            ->editColumn('status', function ($row) {
                if($row->end_date >= Carbon::today()->format('Y-m-d')){
                    return '<span class="label label-success uppercase"> Active </span>';
                }else{
                    return '<span class="label label-danger uppercase"> Expired </span>';
                }
            })
            ->editColumn('amount_to_be_paid', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . $row->amount_to_be_paid;
            })
            ->editColumn('start_date', function ($row) {
                return $row->start_date->toFormattedDateString();
            })
            ->editColumn('end_date', function ($row) {
                return !is_null($row->end_date) ? $row->end_date->toFormattedDateString() : '';
            })
            ->rawColumns(['amount_to_be_paid','start_date','end_date','status', 'locker'])
            ->make();
    }

    public function ajax_create_payments($id)
    {
        if (!$this->data['user']->can("edit_customers")) {
            return App::abort(401);
        }

        $payments = GymMembershipPayment::withoutTrashed()->where('user_id',$id)->where('detail_id',$this->data['user']->detail_id);

        return Datatables::of($payments)
            ->editColumn('payment_source', function ($row) {
                return getPaymentType($row->payment_source);
            })
            ->editColumn('payment_date', function ($row) {
                return $row->payment_date->toFormattedDateString();
            })
            ->editColumn('payment_amount', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . $row->payment_amount;
            })
            ->addColumn('membership', function ($row) {
                if ($row->purchase && $row->purchase->membership) {
                    $title = $row->purchase->membership->title;
                } else {
                    $title = '';
                }
                return $title;
            })
            ->editColumn('payment_id', function ($row) {
                return '<b>' . $row->payment_id . '</b>';
            })
            ->rawColumns(['payment_source', 'payment_id', 'membership'])
            ->make();
    }

    public function ajax_create_due($id)
    {
        if (!$this->data['user']->can("edit_customers")) {
            return App::abort(401);
        }

        $purchase = GymPurchase::withoutTrashed()
        ->where('payment_required','yes')->where('client_id',$id)->where('detail_id',$this->data['user']->detail_id);

        return Datatables::of($purchase)
            ->editColumn('amount_to_be_paid', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . ($row->amount_to_be_paid - $row->paid_amount);
            })
            ->editColumn('purchase_date', function ($row) {
                return $row->purchase_date->toFormattedDateString();
            })
            ->addColumn('membership', function ($row) {
                return $row->membership?->title ?? '';
            })
            ->editColumn('next_payment_date', function ($row) {
                if ((($row->amount_to_be_paid - $row->paid_amount) > 0)) {
                    if (isset($row->next_payment_date)) {
                        return $row->next_payment_date->toFormattedDateString() . ' Due';
                    } else {
                        return 'No Next Pay Date';
                    }
                } else {
                    return 'Payment Complete';
                }
            })
            ->rawColumns([ 'next_payment_date', 'amount_to_be_paid', 'membership'])
            ->make();
    }

    public function ajax_create_product_payments($id)
    {
        if (!$this->data['user']->can("edit_customers")) {
            return App::abort(401);
        }
        $payments = ProductPayment::withoutTrashed()->where('user_id', $id)->where('branch_id', $this->data['user']->detail_id);

        return Datatables::of($payments)
            ->editColumn('payment_source', function ($row) {
                return getPaymentType($row->payment_source);
            })
            ->editColumn('payment_date', function ($row) {
                return $row->payment_date->toFormattedDateString();
            })
            ->editColumn('payment_amount', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . $row->payment_amount;
            })
            ->addColumn('product_name', function ($row) {
                $data = '';
                $products = $row->product_sale->product_name;
                $arr['product_name'] = json_decode($products, true);
                for ($i = 0; $i < count($arr['product_name']); $i++) {
                    $pro = Product::find($arr['product_name'][$i]);
                    if($pro != null){
                        if ($i == 0) {
                            $data = $pro->name ;
                        } else {
                            $data = $data . ', ' . $pro->name ;
                        }
                    }
                }
                return $data;
            })
            ->addColumn('product_quantity', function ($row) {
                return json_decode($row->product_sale->product_quantity, true);
            })

            ->rawColumns(['payment_source','payment_id', 'product_name', 'product_quantity'])
            ->make();
    }

    public function ajax_create_product_due($id)
    {
        if (!$this->data['user']->can("edit_customers")) {
            return App::abort(401);
        }

        $purchase = ProductSales::where('client_id', $id)->where('branch_id', $this->data['user']->detail_id);

        return Datatables::of($purchase)
            ->editColumn('total_amount', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . ($row->total_amount - $row->paid_amount);
            })
            ->editColumn('created_at', function ($row) {
                return date('M d, Y', strtotime($row->created_at));
            })
            ->addColumn('product_name', function ($row) {
                $data = '';
                $arr['product_name'] = json_decode($row->product_name, true);
                for ($i = 0; $i < count($arr['product_name']); $i++) {
                    $pro = Product::find($arr['product_name'][$i]);
                    if($pro != null){
                        if ($i == 0) {
                            $data = $pro->name;
                        } else {
                            $data = $data . ', ' . $pro->name;
                        }
                    }

                }
                return $data;
            })
            ->editColumn('next_payment_date', function ($row) {
                if ((($row->total_amount - $row->paid_amount) > 0)) {
                    if (isset($row->next_payment_date)) {
                        return date('M d, Y', strtotime($row->next_payment_date));
                    } else {
                        return 'No Next Pay Date';
                    }
                } else {
                    return 'Payment Complete';
                }
            })
            ->rawColumns(['product_name', 'created_at', 'next_payment_date'])
            ->make();
    }

    public function ajax_locker_payments($id)
    {
        if (!$this->data['user']->can("edit_customers")) {
            return App::abort(401);
        }

        $payments = LockerPayment::withoutTrashed()->where('client_id',$id)
            ->where('detail_id',$this->data['user']->detail_id)->get();

        return Datatables::of($payments)
            ->editColumn('payment_source', function ($row) {
                return getPaymentType($row->payment_source);
            })
            ->editColumn('payment_date', function ($row) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $row->payment_date)->toFormattedDateString();
            })
            ->editColumn('payment_amount', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . $row->payment_amount;
            })
            ->editColumn('locker_id', function ($row) {
                return $row->reservation->locker->locker_num ?? '';
            })
            ->rawColumns(['payment_source', 'payment_id', 'locker_id'])
            ->make();
    }

    public function ajax_locker_due($id)
    {
        if (!$this->data['user']->can("edit_customers")) {
            return App::abort(401);
        }

        $reservations = LockerReservation::withoutTrashed()->where([['payment_required','yes'],['client_id',$id],['detail_id',$this->data['user']->detail_id]])
            ->get();

        return Datatables::of($reservations)
            ->addColumn('remaining', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . ($row->amount_to_be_paid - $row->paid_amount);
            })
            ->editColumn('start_date', function ($row) {
                return date('M d, Y', strtotime($row->start_date));
            })
            ->editColumn('locker_id', function ($row) {
                return $row->locker->locker_num ?? '';
            })
            ->editColumn('next_payment_date', function ($row) {
                if ((($row->amount_to_be_paid - $row->paid_amount) > 0)) {
                    if (isset($row->next_payment_date)) {
                        return date('M d, Y', strtotime($row->next_payment_date));
                    } else {
                        return 'No Next Pay Date';
                    }
                } else {
                    return 'Payment Complete';
                }
            })
            ->rawColumns(['remaining', 'locker_id', 'next_payment_date'])
            ->make();
    }

    public function uploadImage(ImageRequest $request)
    {
        if (!$this->data['user']->can("edit_customers")) {
            return App::abort(401);
        }
        $id = request()->get('id');
        $gym_client = GymClient::find($id);

        //remove old pic
        if($gym_client->image != null){
            $oldMasterImage = public_path()."/uploads/profile_pic/master/".$gym_client->image;
            $oldThumbImage = public_path()."/uploads/profile_pic/thumb/".$gym_client->image;
            if(file_exists($oldMasterImage)){
                @unlink($oldMasterImage);
            }
            if(file_exists($oldThumbImage)){
                @unlink($oldThumbImage);
            }
        }

        if ($request->ajax()) {

            $output = [];
            $image = request()->file('file');

            $x = intval($request->xCoordOne);
            $y = intval($request->yCoordOne);
            $width = intval($request->profileImageWidth);
            $height = intval($request->profileImageHeight);

            $extension = request()->file('file')->getClientOriginalExtension();
            $filename = request()->get('id') . "-" . rand(10000, 99999) . "." . $extension;
            if (
                !is_null($this->data['gymSettings']->file_storage) || $this->data['gymSettings']->file_storage != '' ||
                !is_null($this->data['gymSettings']->aws_key) || $this->data['gymSettings']->aws_key != '' ||
                !is_null($this->data['gymSettings']->aws_secret) || $this->data['gymSettings']->aws_secret != '' ||
                !is_null($this->data['gymSettings']->aws_region) || $this->data['gymSettings']->aws_region != '' ||
                !is_null($this->data['gymSettings']->aws_bucket) || $this->data['gymSettings']->aws_bucket != ''
            ) {
                $destinationPathMaster = "/uploads/profile_pic/master/$filename";
                $destinationPathThumb = "/uploads/profile_pic/thumb/$filename";

                $image1 = Image::make($image->getRealPath())
                    ->crop($width, $height, $x, $y)
                    ->resize(206, 207);

                $this->uploadImageS3($image1, $destinationPathMaster);

                $image2 = Image::make($image->getRealPath())
                    ->crop($width, $height, $x, $y)
                    ->resize(35, 34);

                $this->uploadImageS3($image2, $destinationPathThumb);
            } else {
                if (!file_exists(public_path() . "/uploads/profile_pic/master/") &&
                    !file_exists(public_path() . "/uploads/profile_pic/thumb/")) {
                    File::makeDirectory(public_path() . "/uploads/profile_pic/master/", $mode = 0777, true, true);
                    File::makeDirectory(public_path() . "/uploads/profile_pic/thumb/", $mode = 0777, true, true);
                }
                $destinationPathMaster = public_path() . "/uploads/profile_pic/master/$filename";
                $destinationPathThumb = public_path() . "/uploads/profile_pic/thumb/$filename";
                $image1 = Image::make($image->getRealPath())
                    ->crop($width, $height, $x, $y)
                    ->resize(206, 207);
                $image1->save($destinationPathMaster);

                $image2 = Image::make($image->getRealPath())
                    ->crop($width, $height, $x, $y)
                    ->resize(40, 40);
                $image2->save($destinationPathThumb);
            }
            $gym_client->image = $filename;
            $gym_client->save();

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

    public function saveWebCamImage($id)
    {
        if (!$this->data['user']->can("edit_customers")) {
            return App::abort(401);
        }

        $img = request()->input('webcam') ?? request()->file('webcam');
        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image = base64_decode($image_parts[1]);
        $fileName = $id . "-" . rand(10000, 99999) .'.'. $image_type;

        if ($this->data['gymSettings']->local_storage == 0) {
            $destinationPathMaster = "profile_pic/master/$fileName";
            $destinationPathThumb = "profile_pic/thumb/$fileName";

            $image1 = Image::make($image)->resize(206, 155);

            $this->uploadImageS3($image1, $destinationPathMaster);

            $image2 = Image::make($image)->resize(35, 34);

            $this->uploadImageS3($image2, $destinationPathThumb);
        } else {
            if (!file_exists(public_path() . "/uploads/profile_pic/master/") &&
                !file_exists(public_path() . "/uploads/profile_pic/thumb/")) {
                File::makeDirectory(public_path() . "/uploads/profile_pic/master/", $mode = 0777, true, true);
                File::makeDirectory(public_path() . "/uploads/profile_pic/thumb/", $mode = 0777, true, true);
            }

            $destinationPathMaster = public_path() . "/uploads/profile_pic/master/$fileName";
            $destinationPathThumb = public_path() . "/uploads/profile_pic/thumb/$fileName";
            $image1 = Image::make($image)->resize(206, 155);
            $image1->save($destinationPathMaster);

            $image2 = Image::make($image)->resize(35, 34);
            $image2->save($destinationPathThumb);
        }

        $gym_client = GymClient::find($id);
        $gym_client->image = $fileName;
        $gym_client->save();

        return Reply::redirect(route('gym-admin.client.show', $id), "Clients Image uploaded successfully");
    }

    public function registerEnquiry($id)
    {
        if (!$this->data['user']->can("add_customers")) {
            return App::abort(401);
        }

        $this->data['title'] = "Add Customer";
        $this->data['enquiry'] = GymEnquiries::gymEnquiry($this->data['user']->detail_id, $id);
        return View::make('gym-admin.gymclients.register_enquiry', $this->data);
    }

    public function sendSms($clientID){
        $data = GymClient::join('business_customers', 'business_customers.customer_id', '=', 'gym_clients.id')
            ->join('common_details', 'common_details.id', '=', 'business_customers.detail_id')
            ->where('gym_clients.is_client','yes')
            ->where('business_customers.detail_id', '=', $this->data['user']->detail_id)
            ->select('common_details.title as company','gym_clients.first_name', 'gym_clients.email', 'gym_clients.mobile', 'gym_clients.joining_date', 'gym_clients.middle_name', 'gym_clients.last_name')
            ->find($clientID)->toArray();
        $temp = Template::businessTemplateMessage($this->data['user']->detail_id,'registration');
        $message = $temp->renderSMS($temp->message,$data);
        $sms = new CustomerSms(array(
            'message' => $message,
            'status' => 0,
            'phone' => $data['mobile']
        ));
        $sms->recipient_id = $clientID;
        $sms->sender_id = $this->data['user']->id;
        $sms->save();
        $job = new SendCustomerSms($sms);
        $this->dispatch($job);
    }
}

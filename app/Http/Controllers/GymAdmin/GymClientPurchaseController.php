<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Helpers\ADMSHelper;
use App\Http\Requests\GymAdmin\Subscriptions\ExtendSubscriptionRequest;
use App\Http\Requests\GymAdmin\Subscriptions\ReminderRequest;
use App\Http\Requests\GymAdmin\Subscriptions\RenewSubscriptionRequest;
use App\Http\Requests\GymAdmin\Subscriptions\StoreRequest;
use App\Http\Requests\GymAdmin\Subscriptions\UpdateRequest;
use App\Jobs\SendCustomerSms;
use App\Models\CustomerSms;
use App\Models\Device;
use App\Models\Shift;
use App\Models\GymClient;
use App\Models\GymClientReminderHistory;
use App\Models\GymMembership;
use App\Models\GymMembershipPayment;
use App\Models\GymMembershipExtend;
use App\Models\GymPurchase;
use App\Models\GymSetting;
use App\Models\Template;
use App\Notifications\ExtendSubscriptionNotification;
use App\Notifications\RenewSubscriptionNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Yajra\Datatables\Datatables;

class GymClientPurchaseController extends GymAdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['manageMenu'] = 'active';
    }

    public function index()
    {
        if (!$this->data['user']->can("view_subscriptions")) {
            return App::abort(401);
        }

        $this->data['subscriptionMenu'] = 'active';
        $this->data['pendingCount']     = GymPurchase::where('detail_id', $this->data['user']->detail_id)
            ->where('status', '=', 'pending')
            ->count();

        $this->data['expireCount']     = GymPurchase::where('detail_id', $this->data['user']->detail_id)
            ->where('status', '!=', 'pending')
            ->where('expires_on', '<', today())
            ->count();

        $this->data['renewCount']     = GymPurchase::where('detail_id', $this->data['user']->detail_id)
            ->where('is_renew',1)
            ->count();

        $this->data['deletedCount']     = GymPurchase::onlyTrashed()->where('detail_id', $this->data['user']->detail_id)
            ->count();
        if (Session::has('user_id')) {
            Session::forget('user_id');
        }

        $this->data['title'] = "Client Purchase";
        return View::make('gym-admin.purchase.index', $this->data);
    }

    public function create()
    {
        if (!$this->data['user']->can("add_subscriptions")) {
            return App::abort(401);
        }

        $this->data['subscriptionMenu'] = 'active';

        $this->data['clients'] = GymClient::join('business_customers', 'business_customers.customer_id', '=', 'gym_clients.id')
            ->active()->where('gym_clients.is_client','yes')
            ->where('business_customers.detail_id', '=', $this->data['user']->detail_id)
            ->get();

        $this->data['title']   = "New Purchase";
        $this->data['user_id'] = 0;
        $this->data['isRedeem'] = null ;
        $this->data['memberships'] = GymMembership::membershipByBusiness($this->data['user']->detail_id);
        $this->data['paymentSources'] = listPaymentType() ;
        return view('gym-admin.purchase.create', $this->data);
    }

    public function userCreate($id,$isRedeem = null)
    {
        if (!$this->data['user']->can("add_subscriptions")) {
            return App::abort(401);
        }

        $this->data['subscriptionMenu'] = 'active';
        $this->data['title']   = "New Purchase";

        $this->data['clients'] = GymClient::findBusinessClient($this->data['user']->detail_id,$id);
        if (is_null($this->data['clients'])) {
            return App::abort(401);
        }
        $this->data['isRedeem'] = $isRedeem ;
        if($isRedeem != null){
            $this->data['memberships'] = GymMembership::where('id',$isRedeem)->get();
        }else{
            $this->data['memberships'] = GymMembership::membershipByBusiness($this->data['user']->detail_id);
        }
        $this->data['paymentSources'] = listPaymentType() ;
        return view('gym-admin.purchase.create', $this->data);
    }

    public function store(StoreRequest $request)
    {
        if (!$this->data['user']->can("add_subscriptions")) {
            return App::abort(401);
        }

        $memberships = GymMembership::find($request->get('membership_id'));
        if ($request->get('discount') >  $memberships->price) {
            return Reply::error("Discount is greater than Cost");
        }
        if ($request->get('payment_amount') >  $request->get('amount_to_be_paid')) {
            return Reply::error("Payment amount is greater than Cost");
        }
        $durationType = $memberships->duration_type;
        $inputData = $request->all();
        $inputData['purchase_date']    = Carbon::createFromFormat('m/d/Y', $request->get('purchase_date'));
        $inputData['start_date']       = Carbon::createFromFormat('m/d/Y', $request->get('start_date'));

        if($durationType != 'unlimited'){
            $expireDate    = GymPurchase::getSubscriptionExpire($memberships->id,$request->get('start_date'));
        }else {
            $expireDate = null;
        }
        $inputData['expires_on']    = $expireDate;
        $inputData['discount'] = $request->get('discount') ?? 0;
        $inputData['client_id'] = $request->get('user_id');
        $inputData['detail_id'] = $this->data['user']->detail_id;
        $inputData['payment_required'] = $request->get('amount_to_be_paid') > 0 ? 'yes' : 'no';

        if($request->get('is_redeem') == 1){
            $inputData['next_payment_date'] = null;
            $inputData['remarks'] = $request->get('remarks') ?? 'Redeem Offer Subscription';
        }else{
            $inputData['next_payment_date'] = Carbon::createFromFormat('m/d/Y', $request->get('next_payment_date'));
        }
        $purchase = GymPurchase::create($inputData);

        //add purchase payment
        if($request->get('payment_amount') > 0){
            if($request->get('payment_date') != null){
                $date = Carbon::createFromFormat('m/d/Y', $request->get('payment_date'));
            }else{
                $date = now()->format('Y-m-d');
            }
            $payment                 = new GymMembershipPayment();
            $payment->user_id        = $purchase->client_id;
            $payment->payment_amount = $request->get('payment_amount');
            $payment->purchase_id    = $purchase->id;
            $payment->payment_source = $request->get('payment_source');
            $payment->payment_date   = $date;
            $payment->remarks        = $request->get('remarks');
            $payment->detail_id      = $this->data['user']->detail_id;
            $payment->payment_id     = 'P-'.rand(1000,9999);
            $payment->save();

            $purchase->paid_amount      += $request->get('payment_amount');
            if ($purchase->amount_to_be_paid == $request->get('payment_amount')) {
                $purchase->payment_required = "no";
                $purchase->next_payment_date = null;
                addRedeemPointToUser($payment->purchase_id);
            }else{
                $purchase->payment_required = "yes";
                if($request->get('next_payment_date') == null){
                    $date = now()->addDays(2)->format('Y-m-d');
                }else{
                    $date = Carbon::createFromFormat('m/d/Y', $request->get('next_payment_date'));
                }
                $purchase->next_payment_date = $date;
            }
            $purchase->save();

        }

         //Enroll customer to ADMS server
         if($this->data['common_details']->has_device == true && $durationType != 'unlimited'){
            $gymClient = GymClient::findBusinessClient($this->data['user']->detail_id,$request->get('user_id'))->first();

            $devices = Device::where('device_status',1)->where('detail_id',$this->data['user']->detail_id)->get();
            $shifts = Shift::where('detail_id',$this->data['user']->detail_id)->get();

            $gymClient->shifts()->sync($shifts);
            $gymClient->devices()->sync($devices);

            foreach($devices as $device){
                $userData = [
                    'userId' => $gymClient->id,
                    'name' => $gymClient->fullName,
                    'card' => null,
                    'category' => 0,
                ];
                $status = ADMSHelper::updateUserInfo($device->serial_num,$device->code,$userData);
                if($status === true){
                    $gymClient->syncLog()->create([
                        'synced' => true,
                        'sync_on' => now()
                    ]);
                }else{
                    $gymClient->syncLog()->create([
                        'synced' => false,
                        'sync_on' => now()
                    ]);
                }
            }
        }

        return Reply::redirect(
            route('gym-admin.client-purchase.index'),'Purchase Added Successfully');

    }

    public function ajax_create($type = null)
    {
        if (!$this->data['user']->can("view_subscriptions")) {
            return App::abort(401);
        }

        $query = GymPurchase::query();
        $query = $query->select('gym_clients.first_name','gym_clients.middle_name', 'gym_clients.last_name', 'amount_to_be_paid','paid_amount', 'gym_memberships.title as membership','gym_client_purchases.is_renew', 'gym_client_purchases.start_date', 'next_payment_date', 'expires_on',
            'gym_client_purchases.id','gym_memberships.duration as duration','gym_memberships.duration_type as duration_type','gym_client_purchases.status as status','gym_clients.status as client_status',
            'gym_membership_freeze.start_date as freeze_date','gym_client_purchases.is_redeem','gym_clients.username as username',
            'gym_client_purchases.client_id')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'client_id')
            ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'membership_id')
            ->leftJoin('gym_membership_freeze', 'gym_membership_freeze.purchase_id', '=', 'gym_client_purchases.id')
            ->where('gym_client_purchases.detail_id', '=', $this->data['user']->detail_id);

        if($type == 'expired'){
            $purchase = $query->where('expires_on','<',today());
        }elseif($type == 'renew'){
            $purchase = $query->where('is_renew',1);
        }else{
            $purchase = $query;
        }
        return Datatables::of($purchase)
            ->editColumn('gym_clients.first_name', function ($row) {
                $name = ucwords($row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name);
                return '<a href="'. route('gym-admin.client.show', $row->client_id).'">'.
                $name .'</a>' ;
            })
            ->editColumn('amount_to_be_paid', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . $row->amount_to_be_paid;
            })
            ->editColumn('paid_amount', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . ($row->amount_to_be_paid - $row->paid_amount);
            })
            ->addColumn('membership', function ($row) {
                return $row->membership;
            })
            ->editColumn('gym_clients.username', function ($row) {
                return $row->username;
            })
            ->addColumn('action', function ($row) {
                if($row->status == "freeze"){
                    $action = '<div class="btn-group"><button class="btn red btn-xs" type="button"><i class="fa fa-pause"></i>Freeze
                    </button></div>';
                }else{
                    $action = '<div class="btn-group"><button class="btn blue btn-xs dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-gears"></i> <span class="hidden-xs hidden-medium">Actions</span>
                    <i class="fa fa-angle-down"></i></button>
                    <ul class="dropdown-menu pull-right" role="menu">';

                    if(($row->amount_to_be_paid - $row->paid_amount) > 0){
                        $action .= '<li><a href="' . route('gym-admin.client-purchase.show', $row->id) . '"> <i class="fa fa-edit"></i>Edit</a>
                            </li><li><a href="javascript:;" data-id="' . $row->id . '" class="remove-purchase"> <i class="fa fa-trash"></i>Remove </a>
                            </li><li> <a class="add-payment" data-id="' . $row->id . '"  href="javascript:;"><i class="fa fa-plus"></i> Add Payment </a>
                            </li>';
                    }

                    if (($row->amount_to_be_paid - $row->paid_amount) == 0 && $row->duration_type != 'unlimited' && $row->client_status == 1) {
                        $action .= '<li><a class="renew-subscription" data-id="' . $row->id . '"  href="javascript:;"><i class="icon-refresh"></i>  Renew Subscription</a>
                        </li>';
                    }

                    if ($this->data['user']->can("subscription_extend_features") && $row->duration_type != 'unlimited') {
                        $action .= '<li><a class="extend-subscription" data-id="' . $row->id . '"  href="javascript:;"><i class="fa fa-expand"></i>  Extend Subscription</a>
                        </li>';
                        if( $row->expires_on > now()){
                            if($row->status == 'freeze_pending'){
                                $action .= '<li><a href=""><i class="fa fa-pause"></i>Freeze On '.date('M d,Y',strtotime($row->freeze_date)).'</a>
                                </li>';
                            }else{
                                $action .= '<li><a class="freeze-subscription" data-id="' . $row->id . '"  href="javascript:;"><i class="fa fa-pause"></i>  Freeze Subscription</a>
                                </li>';
                            }
                        }
                    }

                    $action .= '<li><a class="show-subscription-reminder" data-id="' . $row->id . '"  href="javascript:;"><i class="fa fa-send"></i>  Send Renew Reminder</a>
                            </li></ul></div>';
                }

                return $action;
            })
            ->editColumn('start_date', function ($row) {
                $action = '';
                if($row->is_renew) {
                    $action = '<br><span class="label label-info">Renew</span>';
                }
                if($row->is_redeem) {
                    $action = '<br><span class="label label-warning">Redeem</span>';
                }
                return  $row->start_date->toFormattedDateString() .$action;
            })
            ->editColumn('next_payment_date', function ($row) {
                if ((($row->amount_to_be_paid - $row->paid_amount) > 0)) {
                    if (isset($row->next_payment_date)) {
                        return $row->next_payment_date->toFormattedDateString() . ' <label class="label label-danger">Due</label>';
                    } else {
                        return '<label class="label label-warning">No Next Pay Date</label>';
                    }
                } else {
                    return '<label class="label label-success">Payment Complete</label>';
                }
            })
            ->editColumn('expires_on', function ($row) {
                if (!is_null($row->expires_on)) {
                    $status = 'danger';
                    $action = '';
                    if($row->expires_on > Carbon::now()){
                        $status = 'success';
                    }
                    if($row->status == 'extend') {
                        $action = '<br><span class="label label-info">Extend</span>';
                    }
                    return $row->expires_on->toFormattedDateString() . $action . ' <label class="label label-'.$status.'">'.$row->expires_on->diffForHumans().'</label>';
                } elseif($row->duration_type == 'unlimited'){
                    return 'Unlimited Package';
                }else {
                    return '-';
                }

            })
            ->rawColumns(['action','next_payment_date','expires_on','membership','start_date','gym_clients.first_name','gym_clients.username'])
            ->make(true);
    }

    public function show($id)
    {
        if (!$this->data['user']->can("edit_subscriptions")) {
            return App::abort(401);
        }
        $this->data['subscriptionMenu'] = 'active';
        $this->data['title']            = "Edit Purchase";
        $this->data['purchase']         = GymPurchase::with('membership')->find($id);
        $this->data['purchaseTitle'] = $this->data['purchase']->membership->title;

        return View::make('gym-admin.purchase.edit', $this->data);
    }

    public function update(UpdateRequest $request, $id)
    {
        if (!$this->data['user']->can("edit_subscriptions")) {
            return App::abort(401);
        }
        $purchase = GymPurchase::find($id);
        $memberships = GymMembership::find($purchase->membership_id);

        if ($request->get('discount') >=  $memberships->price) {
            return Reply::error("Discount is greater than Cost");
        }

        $inputData = $request->all();
        $inputData['start_date']       = Carbon::createFromFormat('m/d/Y', $request->get('start_date') ?? $purchase->start_date);
        $inputData['next_payment_date'] = Carbon::createFromFormat('m/d/Y', $request->get('next_payment_date') ?? now()->format('m/d/Y'));
        $inputData['payment_required'] = 'yes';
        if ($request->status == 'on') {
            $inputData['status'] = 'active';
        } else {
            $inputData['status'] = 'pending';
        }
        if($memberships->duration_type != 'unlimited'){
            $expireDate    = GymPurchase::getSubscriptionExpire($memberships->id,$request->get('start_date') ?? $purchase->start_date);
        }else {
            $expireDate = null;
        }
        $inputData['expires_on']    = $expireDate;
        $inputData['discount'] = $request->get('discount') ?? 0;
        $purchase->update($inputData);
        return Reply::redirect(route('gym-admin.client-purchase.index'), 'Subscription updated successfully.');
    }


    public function destroy($id, Request $request)
    {
        if (!$this->data['user']->can("delete_subscriptions")) {
            return App::abort(401);
        }

        if ($request->ajax()) {
            $purchase = GymPurchase::find($id);
            if($purchase->paid_amount > 0) {
                return Reply::error('Unable to remove. Subscription has some amount.');
            }
            $purchase->delete();
            return Reply::success('Subscription removed successfully');
        }

        return Reply::error('Request not Valid');
    }

    //Client Due Subscription
    public function clientDues()
    {
        if (!$this->data['user']->can("view_due_payments")) {
            return App::abort(401);
        }
        $this->data['account']            = 'active';
        $this->data['showclientDuesMenu'] = 'active';
        $this->data['title']              = 'Due Payments';
        return View::make('gym-admin.payments.dues', $this->data);
    }

    public function ajaxDues()
    {
        if (!$this->data['user']->can("view_due_payments")) {
            return App::abort(401);
        }

        $purchase = GymPurchase::select('gym_clients.first_name','gym_clients.middle_name', 'gym_clients.last_name','gym_client_purchases.amount_to_be_paid as amount_to_be_paid',
            'gym_client_purchases.purchase_amount as purchase_amount', 'gym_client_purchases.paid_amount as paid',
            'gym_client_purchases.discount as discount', 'gym_client_purchases.next_payment_date as due_date', 'gym_memberships.title as membership'
            , 'gym_client_purchases.id','gym_client_purchases.remarks as remarks','gym_client_purchases.client_id')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'client_id')
            ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'gym_client_purchases.membership_id')
            ->where('gym_client_purchases.detail_id', $this->data['user']->detail_id)
            ->where('gym_client_purchases.payment_required','yes');

        return Datatables::of($purchase)
            ->editColumn('full_name', function ($row) {
                $name = ucwords($row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name);
                return '<a href="'. route('gym-admin.client.show', $row->client_id).'">'.
                $name .'</a>' ;
            })
            ->addColumn('membership', function ($row) {
                return $row->membership ?? '';
            })
            ->editColumn('purchase_amount', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . ($row->amount_to_be_paid);
            })
            ->editColumn('paid', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . ($row->paid);
            })
            ->editColumn('remaining_amount', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . ($row->amount_to_be_paid - $row->paid);
            })
            ->editColumn('discount', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . $row->discount;
            })
            ->editColumn('due_date', function ($row) {
                if ($row->due_date != null) {
                    return Carbon::createFromFormat('Y-m-d', $row->due_date)->toFormattedDateString();
                } else {
                    return 'No due - date';
                }
            })
            ->addColumn('action', function ($row) {
                return "<div class=\"btn-group\">
                            <button class=\"btn blue btn-xs dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"fa fa-gears\"></i> <span class=\"hidden-xs hidden-medium\">Actions</span>
                                <i class=\"fa fa-angle-down\"></i>
                            </button>
                            <ul class=\"dropdown-menu  pull-right\" role=\"menu\">
                                <li>
                                    <a  data-id=\"$row->id\" class=\"show-reminder\"><i class=\"fa fa-send\"></i> Send Reminder
                                </a>
                                </li>
                                <li>
                                    <a class=\"add-payment\" data-id=\"$row->id\"  href=\"javascript:;\"><i class=\"fa fa-plus\"></i> Add Payment </a>
                                </li>
                         </ul>
                        </div>";
            })
            ->rawColumns(['action','due_date','full_name','membership','purchase_amount'])
            ->make(true);
    }

    public function showModel($id)
    {
        $payment_details = GymPurchase::select('first_name','middle_name', 'last_name', 'email', 'mobile', 'gym_memberships.title as membership', 'paid_amount', 'amount_to_be_paid')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'client_id')
            ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'membership_id')
            ->where('gym_client_purchases.id', '=', $id)
            ->first();

        $this->data['smsSetting']   = GymSetting::select('detail_id', 'sms_status')->where('detail_id', $this->data['user']->detail_id)->get();
        $this->data['emailSetting'] = GymSetting::select('detail_id', 'email_status')->where('detail_id', $this->data['user']->detail_id)->get();
        $this->data['client_data'] = $payment_details;
        $this->data['id']          = $id;
        return View::make('gym-admin.payments.sendreminder', $this->data);
    }

    public function sendReminder(ReminderRequest $request)
    {
        $email      = $request->get('email');
        $mobile     = $request->get('mobile');
        $payment    = $request->get('payment');
        $membership = $request->get('membership');
        $offer      = $request->get('offer');
        $purchaseId = $request->get('purchaseId');
        $sendEmail  = $request->get('sendEmail');
        $sendSms    = $request->get('sendSms');

        if ($membership != '') {
            $type = $membership;
        } else {
            $type = $offer;
        }

        $text     = 'Dear Customer, your payment of NPR ' . $payment . ' is due. Please deposit asap. '.$this->data['common_details']->title;
        $smsText  = 'Dear Customer, your payment of NPR ' . $payment . ' is due. Please deposit asap. '.$this->data['common_details']->title;
        $eText    = $text;
        $eTitle   = "Payment Reminder for " . $type;
        $eHeading = 'Payment Reminder';

        // For Mail and SMS
        if ($sendEmail === 'true') {
            try {
                $this->emailNotification($email, $eText, $eTitle, $eHeading);
            } catch (\Exception $e) {

            }
        }
        if ($sendSms === 'true') {
            $this->smsNotification([$mobile], $smsText);
        }

        $purchase = GymPurchase::find($purchaseId);

        // Create log
        $history = [
            'client_id'     => $purchase->client_id,
            'purchase_id'   => $purchaseId,
            'detail_id'     => $this->data['user']->detail_id,
            'reminder_text' => $text,
            'mobile'        => $mobile,
            'email'         => $email
        ];

        GymClientReminderHistory::create($history);

        return Reply::success('Reminder sent successfully');
    }

    public function reminderHistory()
    {
        if (!$this->data['user']->can("view_due_payments")) {
            return App::abort(401);
        }
        $this->data['account']                    = 'active';
        $this->data['paymentMenu']                = 'active';
        $this->data['title']                      = 'Reminder History';
        $this->data['paymentreminderHistoryMenu'] = 'active';
        $history = GymClientReminderHistory::where('detail_id', $this->data['user']->detail_id)->get();
        return view('gym-admin.payments.reminder_history', $this->data);
    }

    public function ajaxReminderHistory()
    {
        if (!$this->data['user']->can("view_due_payments")) {
            return App::abort(401);
        }

        $history = GymClientReminderHistory::where('detail_id', $this->data['user']->detail_id)->get();
        return Datatables::of($history)
            ->editColumn('client_id', function ($row) {
                return ucwords($row->client?->first_name . ' ' . $row->client?->middle_name . ' ' . $row->client?->last_name);
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d F, Y');
            })
            ->editColumn('reminder_text', function ($row) {
                return $row->reminder_text;
            })
            ->editColumn('mobile', function ($row) {
                return $row->mobile;
            })
            ->removeColumn('detail_id')
            ->removeColumn('purchase_id')
            ->removeColumn('updated_at')
            ->make();
    }

    //Renew Subscription
    public function renewList()
    {
        if (!$this->data['user']->can("view_subscriptions")) {
            return App::abort(401);
        }
        $this->data['subscriptionMenu'] = 'active';
        $this->data['account']            = 'active';
        $this->data['title']              = 'Renew Subscription';
        return View::make('gym-admin.purchase.renew', $this->data);
    }

    public function renewSubscriptionModal($id)
    {
        if (!$this->data['user']->can("add_subscriptions")) {
            return App::abort(401);
        }
        $this->data['purchase'] = GymPurchase::find($id);
        return view('gym-admin.purchase.renew_subscription_modal', $this->data);
    }

    public function renewSubscriptionStore(RenewSubscriptionRequest $request, $id)
    {
        if (!$this->data['user']->can("add_subscriptions")) {
            return App::abort(401);
        }
        $purchase = GymPurchase::find($id);
        $memberships = GymMembership::find($purchase->membership_id);
        if ($request->get('discount') >  $memberships->price) {
            return Reply::error("Discount is greater than Cost");
        }
        $durationType = $memberships->duration_type;
        $inputData = $request->all();
        $inputData['purchase_date']    = Carbon::createFromFormat('m/d/Y', $request->get('purchase_date'));
        $inputData['start_date']       = Carbon::createFromFormat('m/d/Y', $request->get('start_date'));
        $inputData['next_payment_date'] = Carbon::createFromFormat('m/d/Y', $request->get('next_payment_date'));

        $date = Carbon::today()->format('Y-m-d');
        if($durationType != 'unlimited'){
            $expireDate    = GymPurchase::getSubscriptionExpire($memberships->id,$request->get('start_date'));
            if(!$purchase->expires_on->lt($date)){
                $remain = $purchase->expires_on->diffInDays($date);
                $expireDate = $expireDate->addDays($remain);
            }
        }else {
            $expireDate = null;
        }
        $inputData['expires_on']    = $expireDate;
        $inputData['discount'] = $request->get('discount') ?? 0;
        $inputData['payment_required'] = 'yes';
        $inputData['is_renew'] = 1;
        $inputData['membership_id'] = $purchase->membership_id;
        $inputData['client_id'] = $purchase->client_id;
        $inputData['detail_id'] = $purchase->detail_id;
        $renew = GymPurchase::create($inputData);

        if($this->data['gymSettings']->email_status == 'enable' || $this->data['gymSettings']->sms_status == 'enabled') {
            if($this->data['options']['membership_renew_status'] == 1 && $this->data['options']['membership_renew_notify'] == 'both'){
                $this->sendClientPurchaseSMS($purchase->id,'renew');
            }

            if($this->data['options']['membership_renew_status'] == 1 && $this->data['options']['membership_renew_notify'] == 'sms'){
                $this->sendClientPurchaseSMS($purchase->id,'renew');
            }

            if($this->data['options']['membership_renew_status'] == 1 && $this->data['options']['membership_renew_notify'] == 'email'){
                try{
                    $user = GymClient::find($purchase->client_id);
                    $user->notify(new ExtendSubscriptionNotification($renew));
                }catch (\Exception $e){

                }
            }
        }

        // $setting = GymSetting::where('detail_id',$this->data['user']->detail_id)->first();
        // if($setting->email_status == 'enabled'){
        //     try{
        //         $user = GymClient::find($purchase->client_id);
        //         $user->notify(new RenewSubscriptionNotification($renew));
        //     }catch (\Exception $e){

        //     }
        // }

         //Renew customer to ADMS server
         if($this->data['common_details']->has_device == true && $durationType != 'unlimited'){
            $gymClient = GymClient::findBusinessClient($this->data['user']->detail_id,$renew->client_id)->first();

            $devices = Device::where('device_status',1)->where('detail_id',$this->data['user']->detail_id)->get();
            $shifts = Shift::where('detail_id',$this->data['user']->detail_id)->get();

            $gymClient->shifts()->sync($shifts);
            $gymClient->devices()->sync($devices);

            foreach($devices as $device){
                $status = ADMSHelper::sendUserDevice($gymClient->customer_id,$device->code);
                if($status === true){
                    $gymClient->update([
                        'is_device_deleted' => false,
                        'is_denied' => false,
                        'is_expired' => false,
                    ]);
                }
            }

        }

        if($renew->amount_to_be_paid == 0){
            return Reply::success('Subscription Renewed Successfully');
        }else{
            return Reply::redirect(
                route('gym-admin.membership-payment.user-create',['clientId'=>$renew->client_id,'membershipId'=>$renew->membership_id]),
                'Subscription Renewed Successfully');
        }
    }

    public function subscriptionReminderModal($id)
    {
        $payment_details = GymPurchase::select('first_name', 'middle_name', 'last_name', 'email', 'mobile', 'gym_memberships.title as membership', 'gym_client_purchases.id')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'client_id')
            ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'membership_id')
            ->where('gym_client_purchases.id', '=', $id)
            ->first();

        $this->data['smsSetting']   = GymSetting::select('detail_id', 'sms_status')->where('detail_id', $this->data['user']->detail_id)->get();
        $this->data['emailSetting'] = GymSetting::select('detail_id', 'email_status')->where('detail_id', $this->data['user']->detail_id)->get();

        $this->data['client_data'] = $payment_details;
        $this->data['id']          = $id;
        return View::make('gym-admin.purchase.sendreminder', $this->data);
    }

    public function sendRenewReminder(Request $request)
    {
        $email      = $request->get('email');
        $mobile     = $request->get('mobile');
        $membership = $request->get('membership');
        $offer      = $request->get('offer');
        $purchaseId = $request->get('purchaseId');

        $purchase = GymPurchase::find($purchaseId);

        if ($membership != '') {
            $type = $membership;
        } else {
            $type = $offer;
        }

        $text  = 'Dear Customer, your subscription of ' . $membership . ' is expiring on ' . ((isset($purchase->expires_on)) ? $purchase->expires_on->format('d M, Y') : '') . '. Please renew asap. '.$this->data['common_details']->title;
        $eTitle   = "Subscription Renewal Reminder for " . $type;
        $eHeading = 'Subscription Renewal Reminder';

        // For Mail and SMS
        if ($request->get('emailReminder') == 1) {
            try {
                $this->emailNotification($email, $text, $eTitle, $eHeading);

            } catch (\Exception $e) {

            }
        }

        if ($request->get('smsReminder') == 1) {
            $this->smsNotification([$mobile], $text);
        }
        $customerSmsData = [
            'message' => $text,
            'status' => 1,
            'phone' => $mobile,
            'recipient_id'     => $purchase->client_id,
            'sender_id'     => $this->data['user']->id,
        ];
        CustomerSms::create($customerSmsData);
        return Reply::success('Reminder sent successfully');
    }

    //Pending Subscription
    public function pendingSubscription()
    {
        if (!$this->data['user']->can("view_subscriptions")) {
            return App::abort(401);
        }
        $this->data['manageMenu']       = 'active';
        $this->data['subscriptionMenu'] = 'active';
        $this->data['title']            = "Pending Client Purchase";

        $this->data['deletedCount']     = GymPurchase::onlyTrashed()->where('detail_id', '=', $this->data['user']->detail_id)
            ->count();

        return view('gym-admin.purchase.pending-subscription', $this->data);
    }

    public function ajaxPendingSubscription()
    {
        $purchase = GymPurchase::select('gym_clients.first_name', 'gym_clients.middle_name', 'gym_clients.last_name', 'amount_to_be_paid', 'paid_amount', 'gym_memberships.title as membership', 'start_date',
        'next_payment_date', 'expires_on', 'gym_client_purchases.id','gym_client_purchases.client_id','gym_clients.username')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'client_id')
            ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'membership_id')
            ->where('gym_client_purchases.detail_id', '=', $this->data['user']->detail_id)
            ->where('gym_client_purchases.status', '=', 'pending');

        return Datatables::of($purchase)
            ->editColumn('gym_clients.first_name', function ($row) {
                $name = ucwords($row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name);
                return '<a href="'. route('gym-admin.client.show', $row->client_id).'">'.
                $name .'</a>' ;
            })
            ->editColumn('amount_to_be_paid', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' .$row->amount_to_be_paid;
            })
            ->addColumn('remaining', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' .($row->amount_to_be_paid - $row->paid_amount);
            })
            ->addColumn('membership', function ($row) {
                return $row->membership;
            })
            ->editColumn('gym_clients.username', function ($row) {
                return $row->username;
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group">
                    <button class="btn blue btn-xs dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-gears"></i><span class="hidden-xs hidden-medium">Actions</span>
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <li>
                            <a href="' . route('gym-admin.client-purchase.show', $row->id) . '"> <i class="fa fa-edit"></i>Edit</a>
                        </li>
                        <li>
                            <a href="javascript:;" data-id="' . $row->id . '" class="remove-purchase"> <i class="fa fa-trash"></i>Remove </a>
                        </li>
                    </ul>
                </div>';
            })
            ->editColumn('start_date', function ($row) {
                return $row->start_date->toFormattedDateString();
            })
            ->editColumn('next_payment_date', function ($row) {
                if (!is_null($row->next_payment_date)) {
                    if (Carbon::now('Asia/Kathmandu')->diffInDays($row->next_payment_date, false) <= 0) {
                        return $row->next_payment_date->toFormattedDateString() . ' <label class="label label-danger">Due</label>';
                    } else {
                        return $row->next_payment_date->toFormattedDateString();
                    }
                } else if ($row->amount_to_be_paid <= $row->paid_amount) {
                    return '<label class="label label-success">Payment Complete</label>';
                } else {
                    return '<label class="label label-warning">No Next Pay Date</label>';
                }
            })
            ->editColumn('expires_on', function ($row) {
                if (!is_null($row->expires_on)) {
                    return $row->expires_on->toFormattedDateString();
                } else {
                    return '-';
                }

            })
            ->rawColumns(['amount_to_be_paid','remaining','start_date','next_payment_date', 'action','membership','gym_clients.first_name','gym_clients.username'])
            ->make(true);
    }

    //Deleted Subscription
    public function deletedSubscription()
    {
        if (!$this->data['user']->can("delete_subscriptions")) {
            return App::abort(401);
        }
        $this->data['manageMenu']       = 'active';
        $this->data['subscriptionMenu'] = 'active';
        $this->data['title']            = "Deleted Client Purchase";

        $this->data['pendingCount']     = GymPurchase::where('detail_id', '=', $this->data['user']->detail_id)
            ->where('status', '=', 'pending')
            ->count();

        return view('gym-admin.purchase.deleted-subscription', $this->data);
    }

    public function ajaxDeletedSubscription()
    {
        $purchase = GymPurchase::onlyTrashed()->select('gym_clients.first_name', 'gym_clients.middle_name', 'gym_clients.last_name', 'amount_to_be_paid', 'paid_amount', 'gym_memberships.title as membership', 'start_date', 'gym_client_purchases.deleted_at',
        'expires_on', 'gym_client_purchases.id','gym_client_purchases.client_id','gym_clients.username')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'client_id')
            ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'membership_id')
            ->where('gym_client_purchases.detail_id', '=', $this->data['user']->detail_id);

        return Datatables::of($purchase)
            ->editColumn('gym_clients.first_name', function ($row) {
                $name = ucwords($row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name);
                return '<a href="'. route('gym-admin.client.show', $row->client_id).'">'.
                $name .'</a>' ;
            })
            ->editColumn('amount_to_be_paid', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' .$row->amount_to_be_paid;
            })
            ->addColumn('remaining', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' .($row->amount_to_be_paid - $row->paid_amount);
            })
            ->addColumn('membership', function ($row) {
                return $row->membership;
            })
            ->editColumn('gym_clients.username', function ($row) {
                return $row->username;
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group">
                    <button class="btn blue btn-xs dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-gears"></i><span class="hidden-xs hidden-medium">Actions</span>
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <li>
                            <a href="javascript:;" data-id="' . $row->id . '" class="restore-purchase"> <i class="fa fa-undo"></i>Restore</a>
                        </li>
                        <li>
                            <a href="javascript:;" data-id="' . $row->id . '" class="remove-purchase"> <i class="fa fa-trash"></i>Remove </a>
                        </li>
                    </ul>
                </div>';
            })
            ->editColumn('start_date', function ($row) {
                return $row->start_date->toFormattedDateString();
            })
            ->editColumn('gym_client_purchases.deleted_at', function ($row) {
                return $row->deleted_at->toFormattedDateString();
            })
            ->editColumn('expires_on', function ($row) {
                if (!is_null($row->expires_on)) {
                    return $row->expires_on->toFormattedDateString();
                } else {
                    return '-';
                }

            })
            ->rawColumns(['amount_to_be_paid','remaining', 'action','membership','gym_clients.first_name','gym_clients.username'])
            ->make(true);
    }

    public function restore($id) {
         if (!$this->data['user']->can("edit_subscriptions")) {
             return App::abort(401);
         }
        $purchase = GymPurchase::withTrashed()->find($id);
        $purchase->restore();
        return Reply::success('Subscription restore successfully');
    }

    public function delete($id, Request $request)
    {
        if (!$this->data['user']->can("delete_subscriptions")) {
            return App::abort(401);
        }

        if ($request->ajax()) {
            $purchase = GymPurchase::withTrashed()->find($id);
            $purchase->forceDelete();
            return Reply::success('Subscription removed permanently.');
        }

        return Reply::error('Request not Valid');
    }

    //Extend Subscription
    public function extendList()
    {
        if (!$this->data['user']->can("subscription_extend_features")) {
            return App::abort(401);
        }
        $this->data['extendsubscriptionMenu'] = 'active';
        $this->data['account']            = 'active';
        $this->data['title']              = 'Extend Subscription';
        return View::make('gym-admin.purchase.extend', $this->data);
    }

    public function ajaxExtendSubscription()
    {
        if (!$this->data['user']->can("subscription_extend_features")) {
            return App::abort(401);
        }

        $purchase = GymMembershipExtend::where('detail_id', $this->data['user']->detail_id)->get();

        return Datatables::of($purchase)
            ->editColumn('client_id', function ($row) {
                $name = $row->client->fullName ?? '' ;
                return '<a href="'. route('gym-admin.client.show', $row->client_id).'">'.
                $name .'</a>' ;
            })
            ->editColumn('purchase_id', function ($row) {
                return $row->purchase->membership->title ?? '' ;
            })
            ->editColumn('extend_by', function ($row) {
                return $row->extendBy->first_name ?? '';
            })
            ->editColumn('extend_to', function ($row) {
                return $row->extend_to->toFormattedDateString();
            })
            ->editColumn('extend_from', function ($row) {
                return $row->extend_from->toFormattedDateString();
            })
            ->rawColumns(['client_id','purchase_id','extend_by'])
            ->make(true);
    }

    public function extendSubscriptionModal($id)
    {
        if (!$this->data['user']->can("subscription_extend_features")) {
            return App::abort(401);
        }
        $this->data['purchase'] = GymPurchase::find($id);
        return view('gym-admin.purchase.extend_subscription_modal', $this->data);
    }

    public function extendSubscriptionStore(ExtendSubscriptionRequest $request, $id)
    {
        if (!$this->data['user']->can("subscription_extend_features")) {
            return App::abort(401);
        }
        $purchase = GymPurchase::find($id);
        if($request->get('extend_from') != null){
            $extendDate = Carbon::createFromFormat('m/d/Y', $request->get('extend_from'));
        }else{
            $extendDate =  $purchase->expires_on;
        }

        $renew                    = new GymMembershipExtend();
        $renew->client_id         = $purchase->client_id;
        $renew->purchase_id       = $purchase->id;
        $renew->extend_by         = $this->data['user']->id;
        $renew->detail_id         = $this->data['user']->detail_id;
        $renew->days              = $request->get('days');
        $renew->extend_from       = $extendDate;
        $renew->reasons           = $request->get('reasons');

        //set extend date;
        $days  = $request->get('days');
        $extend_upto        =   $extendDate->addDays($days);
        $renew->extend_to         = $extend_upto;
        $purchase->expires_on = $extend_upto;
        $purchase->status = 'extend';
        $purchase->save();
        $renew->save();

        if($this->data['gymSettings']->email_status == 'enable' || $this->data['gymSettings']->sms_status == 'enabled') {
            if($this->data['options']['membership_extend_status'] == 1 && $this->data['options']['membership_extend_notify'] == 'both'){
                $this->sendClientPurchaseSMS($purchase->id,'extend');

            }

            if($this->data['options']['membership_extend_status'] == 1 && $this->data['options']['membership_extend_notify'] == 'sms'){
               $this->sendClientPurchaseSMS($purchase->id,'extend');
            }

            if($this->data['options']['membership_extend_status'] == 1 && $this->data['options']['membership_extend_notify'] == 'email'){
                try{
                    $user = GymClient::find($purchase->client_id);
                    $user->notify(new ExtendSubscriptionNotification($renew));
                }catch (\Exception $e){

                }
            }
        }
        return Reply::success('Subscription Extended Successfully');
    }

    public function sendClientPurchaseSMS($purchaseID,$type){

        $data = GymPurchase::has('client')->select('gym_clients.first_name','gym_clients.middle_name', 'gym_clients.last_name','gym_clients.mobile' ,'gym_clients.email',
            'gym_client_purchases.expires_on as expire_date', 'gym_memberships.title as membership','gym_client_purchases.next_payment_date as due_date',
            'gym_client_purchases.paid_amount', 'common_details.title as company','gym_membership_extends.days as day','gym_clients.id as c_id')
            ->selectRaw('(amount_to_be_paid - paid_amount) as due_amount')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'client_id')
            ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'membership_id')
            ->leftJoin('common_details', 'common_details.id', '=', 'gym_client_purchases.detail_id')
            ->leftJoin('gym_membership_extends', 'gym_membership_extends.purchase_id', '=', 'gym_client_purchases.id')
            ->find($purchaseID)->toArray();

        $temp = Template::businessTemplateMessage($this->data['user']->detail_id,$type);
        $message = $temp->renderSMS($temp->message,$data);
        $sms = new CustomerSms(array(
            'message' => $message,
            'status' => 0,
            'phone' => $data['mobile']
        ));
        $sms->recipient_id = $data['c_id'] ;
        $sms->sender_id = $this->data['user']->id;
        $sms->save();
        $job = new SendCustomerSms($sms);
        $this->dispatch($job);
    }

    //Expire Subscription
    public function expireList()
    {
        if (!$this->data['user']->can("view_subscriptions")) {
            return App::abort(401);
        }
        $this->data['subscriptionMenu'] = 'active';
        $this->data['account']            = 'active';
        $this->data['title']              = 'Expire Subscription';

        return View::make('gym-admin.purchase.expired', $this->data);
    }

    public function activeList()
    {
        if (!$this->data['user']->can("view_subscriptions")) {
            return App::abort(401);
        }
        $this->data['subscriptionMenu'] = 'active';
        $this->data['account']            = 'active';
        $this->data['title']              = 'active';

        $this->data['purchases'] = GymPurchase::where('expires_on', '>', now())
        ->where('detail_id', $this->data['user']->detail_id)
        ->groupBy('client_id','membership_id')
        ->orderBy('expires_on','asc')
        ->get();
        return View::make('gym-admin.purchase.inactive', $this->data);
    }

    public function inactiveList()
    {
        if (!$this->data['user']->can("view_subscriptions")) {
            return App::abort(401);
        }
        $this->data['subscriptionMenu'] = 'active';
        $this->data['account']            = 'active';
        $this->data['title']              = 'inactive';

        $active_users = GymClient::getActiveClient($this->data['user']->detail_id)->pluck('id');

       $data = GymPurchase::with('client','membership')->where('expires_on', '<', now())
       ->where('detail_id', $this->data['user']->detail_id)
       ->whereNotIn('client_id',$active_users)
       ->orderBy('expires_on','desc')
       ->get();

       $filteredData = collect($data)
        ->groupBy('client_id', 'membership_id') // Group by user_id and membership_id
        ->map(function ($group) {
            return $group->sortByDesc('expires_on')->first(); // Get the latest entry for each group
        })
        ->values() // Reindex the collection
        ->flatten(1)
        ->toArray();

        $this->data['purchases'] = $filteredData;
        return View::make('gym-admin.purchase.inactive', $this->data);
    }
}



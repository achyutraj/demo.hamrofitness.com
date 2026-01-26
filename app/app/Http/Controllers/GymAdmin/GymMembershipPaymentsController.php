<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Jobs\SendCustomerSms;
use App\Models\CustomerSms;
use App\Models\GymClient;
use App\Models\GymMembershipPayment;
use App\Models\GymPurchase;
use App\Models\Template;
use App\Notifications\AddPaymentNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;

class GymMembershipPaymentsController extends GymAdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['paymentMenu']     = 'active';
        $this->data['showpaymentMenu'] = 'active';
    }

    /**
     * Load the index page for Membership Payments
     */
    public function index()
    {

        if (!$this->data['user']->can("view_payments")) {
            return App::abort(401);
        }
        $this->data['title'] = 'Payments';
        return View::make('gym-admin.payments.index', $this->data);
    }

    public function ajax_create()
    {
        if (!$this->data['user']->can("view_payments")) {
            return App::abort(401);
        }

        $payments = GymMembershipPayment::select('gym_clients.first_name','gym_membership_payments.user_id',
            'payment_amount', 'payment_source', 'payment_date', 'payment_id','gym_memberships.title as membership',
            'gym_membership_payments.id as pid', 'gym_clients.middle_name', 'gym_clients.last_name', 'purchase_id')
            ->leftJoin('gym_client_purchases', 'gym_client_purchases.id', '=', 'gym_membership_payments.purchase_id')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'gym_membership_payments.user_id')
            ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'gym_client_purchases.membership_id')
            ->where('gym_membership_payments.detail_id',  $this->data['user']->detail_id);

        return Datatables::of($payments)
            ->editColumn('gym_clients.first_name', function ($row) {
                $name = ucwords($row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name);
                return '<a href="'. route('gym-admin.client.show', $row->user_id).'">'.
                $name .'</a>' ;
            })
            ->editColumn('payment_source', function ($row) {
                return getPaymentType($row->payment_source);
            })
            ->editColumn('payment_date', function ($row) {
                return $row->payment_date->toFormattedDateString();
            })
            ->editColumn('payment_amount', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . $row->payment_amount;
            })
            ->editColumn('payment_id', function ($row) {
                return '<b>' . $row->payment_id . '</b>';
            })
            ->addColumn('membership', function ($row) {
                return ucfirst($row->membership);
            })
            ->addColumn('action', function ($row) {
            return "<div class=\"btn-group\">
                            <button class=\"btn btn-xs blue dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\" aria-expanded=\"true\"><i class=\"fa fa-gears\"></i><span class=\"hidden-xs\">ACTION</span>
                                <i class=\"fa fa-angle-down\"></i>
                            </button>
                            <ul class=\"dropdown-menu  pull-right\" role=\"menu\">
                                <li>
                                    <a href='" . route("gym-admin.gym-invoice.create-payment-invoice", $row->pid) . "'><i class=\"fa fa-file\"></i> Generate Invoice </a>
                                </li>
                                <li>
                                    <a href='" . route("gym-admin.membership-payment.show", $row->pid) . "'><i class=\"fa fa-edit\"></i> Edit </a>
                                </li>
                                <li>
                                    <a class=\"remove-payment\" data-payment-id=\"$row->pid\"  href=\"javascript:;\"><i class=\"fa fa-trash\"></i> Delete </a>
                                </li>
                         </ul>
                        </div>";
        })
            ->rawColumns(['action','membership','payment_source','payment_id','payment_date','gym_clients.first_name'])
            ->make(true);
    }

    public function ajax_create_deleted()
    {
        if (!$this->data['user']->can("view_payments")) {
            return App::abort(401);
        }

        $payments = GymMembershipPayment::onlyTrashed()
            ->select('gym_clients.first_name',
                'payment_amount',
                'payment_source',
                'payment_date',
                'payment_id',
                'gym_memberships.title as membership',
                'gym_membership_payments.deleted_at',
                'purchase_id',
                'gym_clients.middle_name',
                'gym_clients.last_name',
                'gym_membership_payments.id as pid','gym_membership_payments.user_id')
            ->leftJoin('gym_client_purchases', 'gym_client_purchases.id', '=', 'gym_membership_payments.purchase_id')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'gym_membership_payments.user_id')
            ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'gym_client_purchases.membership_id')
            ->where('gym_membership_payments.detail_id', '=', $this->data['user']->detail_id)
            ->whereNotNull('gym_membership_payments.deleted_at');

        return Datatables::of($payments)
            ->editColumn('gym_clients.first_name', function ($row) {
                $name = ucwords($row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name);
                return '<a href="'. route('gym-admin.client.show', $row->user_id).'">'.
                $name .'</a>' ;
            })
            ->editColumn('payment_source', function ($row) {
                return getPaymentType($row->payment_source);
            })
            ->editColumn('payment_date', function ($row) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $row->payment_date)->toFormattedDateString();
            })
            ->editColumn('payment_amount', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . $row->payment_amount;
            })
            ->editColumn('payment_id', function ($row) {
                return '<b>' . $row->payment_id . '</b>';
            })
            ->addColumn('membership', function ($row) {
                return ucfirst($row->membership);
            })
            ->editColumn('deleted_at', function ($row) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $row->deleted_at)->toFormattedDateString();
            })
            ->rawColumns(['payment_source','membership','payment_id','payment_date','deleted_at','gym_clients.first_name'])
            ->make(true);
    }

    public function create()
    {
        if (!$this->data['user']->can("add_payments")) {
            return App::abort(401);
        }

        $this->data['title']       = 'Add Payment';
        $this->data['userMembership'] = false;
        $this->data['clients']     = GymClient::GetClients($this->data['user']->detail_id)->active()->whereHas('subscriptions',function($q){
                                        $q->where('payment_required','yes');
                                      })->get();
        $this->data['paymentSources'] = listPaymentType() ;
        return View::make('gym-admin.payments.create', $this->data);
    }

    public function userPayCreate($clientId,$membershipId)
    {
        if (!$this->data['user']->can("add_payments")) {
            return App::abort(401);
        }
        $this->data['title']       = 'Add Payment';
        $this->data['clients'] = GymClient::findBusinessClient($this->data['user']->detail_id,$clientId);
        $this->data['userMembership'] = true;
        $this->data['purchases'] = GymPurchase::clientMembershipPurchases($this->data['user']->detail_id,$clientId,$membershipId);
        if (is_null($this->data['clients']) && is_null($this->data['purchases']->first())) {
            return App::abort(404);
        }
        if ($this->data['purchases']->count() > 0) {
            $this->data['amount'] = $this->data['purchases']->first()->amount_to_be_paid;
        } else {
            $this->data['amount'] = 0;
        }
        $this->data['paymentSources'] = listPaymentType() ;
        return View::make('gym-admin.payments.create', $this->data);
    }

    public function store()
    {
        if (!$this->data['user']->can("add_payments")) {
            return App::abort(401);
        }
        $action    = 'membership';
        $validator = Validator::make(request()->all(), GymMembershipPayment::rules($action));
        if ($validator->fails()) {
            return Reply::formErrors($validator);
        } else {

            $purchase                   = GymPurchase::find(request()->get('purchase_id'));
            $remain = $purchase->amount_to_be_paid - $purchase->paid_amount;
            if(request()->get('payment_amount') > $remain){
                return Reply::error("Remaining amount is ".$remain);
            }
            $payment                 = new GymMembershipPayment();
            $payment->user_id        = request()->get('client');
            $payment->payment_amount = request()->get('payment_amount');
            $payment->purchase_id    = request()->get('purchase_id');
            $payment->payment_source = request()->get('payment_source');
            $payment->payment_date   = Carbon::createFromFormat('m/d/Y', request()->get('payment_date')) ?? now()->format('Y-m-d');
            $payment->remarks        = request()->get('remark');
            $payment->detail_id      = $this->data['user']->detail_id;
            $payment->payment_id     = 'P-'.rand(1000,9999);
            $payment->save();

            //Update the details of next payment in gym_client_purchases
            $purchase->paid_amount      += request()->get('payment_amount');
            if ($remain == request()->get('payment_amount')) {
                $purchase->payment_required = "no";
                $purchase->next_payment_date = null;
                addRedeemPointToUser($payment->purchase_id);

            }else{
                $purchase->payment_required = "yes";
                if(request()->get('next_payment_date') == null){
                    $date = now()->addDays(2)->format('Y-m-d');
                }else{
                    $date = Carbon::createFromFormat('m/d/Y', request()->get('next_payment_date'));
                }
                $purchase->next_payment_date = $date;
            }
            $purchase->save();
            if($this->data['gymSettings']->sms_status == 'enabled') {
                if($this->data['options']['customer_payment_status'] == 1 && $this->data['options']['customer_payment_notify'] == 'sms'){
                    $this->sendSms($payment);
                }
            }
            return Reply::redirect(route('gym-admin.membership-payment.index'), 'Payment Added Successfully');
        }
    }

    public function show($id)
    {
        if (!$this->data['user']->can("edit_payments")) {
            return App::abort(401);
        }

        $this->data['title']            = 'Update Payment';
        $this->data['clients']          = GymClient::GetClients($this->data['user']->detail_id);
        $this->data['payment']          = GymMembershipPayment::select('gym_membership_payments.*', 'gym_client_purchases.payment_required', 'gym_client_purchases.next_payment_date as next_date')->leftJoin('gym_client_purchases', 'gym_client_purchases.id', '=', 'gym_membership_payments.purchase_id')->where('gym_membership_payments.id', '=', $id)->first();
        $this->data['purchases']        = GymPurchase::clientPurchases($this->data['payment']->user_id);
        $purchase                       = GymPurchase::find($this->data['payment']->purchase_id);
        $this->data['remaining_amount'] = ($purchase->amount_to_be_paid - $purchase->paid_amount);
        $this->data['paymentSources'] = listPaymentType() ;
        return View::make('gym-admin.payments.edit', $this->data);
    }

    public function update($id)
    {
        if (!$this->data['user']->can("edit_payments")) {
            return App::abort(401);
        }

        $validator = Validator::make(request()->all(), GymMembershipPayment::rules('edit'));

        if ($validator->fails()) {
            return Reply::formErrors($validator);
        } else {
            $payment                 = GymMembershipPayment::find($id);
            $purchase    = GymPurchase::find($payment->purchase_id);
            $remain      = $purchase->amount_to_be_paid - $purchase->paid_amount;
            if(request()->get('payment_amount') > $remain){
                return Reply::error("Remaining amount is ".$remain);
            }

            $old_amount              = $payment->payment_amount;
            $payment->payment_amount = request()->get('payment_amount');
            $payment->payment_source = request()->get('payment_source');
            $payment->payment_date   = Carbon::createFromFormat('m/d/Y', request()->get('payment_date'))->format('Y-m-d');

            $payment->remarks      = request()->get('remark');

            //Update the details of next payment in gym_client_purchases
            $paid_amount = $purchase->paid_amount - $old_amount;
            $purchase->paid_amount      = request()->get('payment_amount') + $paid_amount;
             if ($remain == request()->get('payment_amount')) {
                $purchase->payment_required = "no";
                $purchase->next_payment_date = null;
            }else{
                $purchase->payment_required = "yes";
                if(request()->get('next_payment_date') == null){
                    $date = now()->addDays(2)->format('Y-m-d');
                }else{
                    $date = Carbon::createFromFormat('m/d/Y', request()->get('next_payment_date'));
                }
                $purchase->next_payment_date = $date;
            }
            $purchase->save();
            $payment->save();

            return Reply::redirect(route('gym-admin.membership-payment.index'), 'Payment Updated Successfully');
        }
    }

    public function destroy($id, Request $request)
    {
        if (!$this->data['user']->can("delete_payments")) {
            return App::abort(401);
        }

        if ($request->ajax()) {
            $payment      = GymMembershipPayment::find($id);
            $old_amount   = $payment->payment_amount;
            if ($payment->purchase_id != null) {
                $purchase              = GymPurchase::find($payment->purchase_id);
                $purchase->paid_amount = $purchase->paid_amount - $old_amount;
                $purchase->save();
            }
            GymMembershipPayment::find($id)->delete();
            return Reply::success('Payment removed successfully');
        }
        return Reply::error('Request not Valid');
    }

    public function viewReceipt($id)
    {
        $this->data['payment'] = GymMembershipPayment::find($id);
        return view('gym-admin.payments.receipt', $this->data);
    }

    public function emailReceipt($id)
    {
        $this->data['payment'] = GymMembershipPayment::find($id);
        $content               = view('gym-admin.payments.email_receipt', $this->data)->render();

        $eText = $content;

        $title       = "HamroFitness | Payment acknowledgement #" . $this->data['payment']->payment_id;
        $mailHeading = "Payment Receipt #" . $this->data['payment']->payment_id;
        $eUrl        = NULL;

        $this->emailNotification($this->data['payment']->client->email, $eText, $title, $mailHeading, $eUrl);
        return Reply::success('Receipt sent successfully');
    }

    public function clientPurchases($id)
    {
        $this->data['purchases'] = GymPurchase::clientPurchases($id);
        $view                    = view('gym-admin.purchase.client_purchase_ajax', $this->data)->render();
        return Reply::successWithData('Client purchases fetched', ['data' => $view]);
    }

    public function clientPayment($id)
    {
        $payAmount             = request()->get('amount');
        $this->data['payment'] = GymPurchase::select(DB::raw("(amount_to_be_paid - paid_amount)-$payAmount as 'diff' "))
            ->where('client_id', '=', $id)->first();
        return $this->data;
    }

    public function clientEditPayment($id)
    {
        $payAmount             = request()->get('amount');
        $old_amount            = request()->get('old_amount');
        $this->data['payment'] = GymPurchase::select(DB::raw("(amount_to_be_paid - (paid_amount-$old_amount))-$payAmount as 'diff' "))->where('client_id', '=', $id)->first();
        return $this->data;
    }

    /**
     * Show modal to add payment for a particular subscription
     * */
    public function addPaymentModal($id)
    {
        $this->data['purchase'] = GymPurchase::find($id);
        $this->data['paymentSources'] = listPaymentType();
        return view('gym-admin.payments.add_payment_modal', $this->data);
    }

    /**
     * Save payment from modal
     * */
    public function ajaxPaymentStore($id)
    {
        $purchase  = GymPurchase::find($id);
        $validator = Validator::make(request()->all(), GymMembershipPayment::rules('ajax_add'));

        if ($validator->fails()) {
            return Reply::formErrors($validator);
        } else {
            $remain = $purchase->amount_to_be_paid - $purchase->paid_amount;
            if(request()->get('payment_amount') > $remain){
                return Reply::error("Remaining amount is ".$remain);
            }
            $payment                 = new GymMembershipPayment();
            $payment->user_id        = $purchase->client_id;
            $payment->payment_amount = request()->get('payment_amount');
            $payment->purchase_id    = $purchase->id;
            $payment->payment_source = request()->get('payment_source');
            $payment->payment_date   = Carbon::createFromFormat('m/d/Y', request()->get('payment_date'))->format('Y-m-d');
            $payment->remarks        = request()->get('remark');
            $payment->payment_id     = 'P-'.rand(1000,9999);
            $payment->detail_id      = $purchase->detail_id;
            $payment->save();

            // Update the details of next payment in gym_client_purchases
            $purchase->paid_amount      += request()->get('payment_amount');
            if ($remain == request()->get('payment_amount')) {
                $purchase->payment_required = "no";
                $purchase->next_payment_date = null;
                addRedeemPointToUser($payment->purchase_id);
            }else{
                $purchase->payment_required = "yes";
                if(request()->get('next_payment_date') == null){
                    $date = now()->addDays(2)->format('Y-m-d');
                }else{
                    $date = Carbon::createFromFormat('m/d/Y', request()->get('next_payment_date'));
                }
                $purchase->next_payment_date = $date;
            }
            $purchase->save();
            if($this->data['gymSettings']->sms_status == 'enabled') {
                if($this->data['options']['customer_payment_status'] == 1 && $this->data['options']['customer_payment_notify'] == 'sms'){
                    $this->sendSms($payment);
                }
            }
            return Reply::redirect(route('gym-admin.membership-payment.index'), 'Payment Added Successfully');
        }
    }

    public function sendSms($payment){
        $data = GymMembershipPayment::join('gym_clients', 'gym_membership_payments.user_id', '=', 'gym_clients.id')
        ->leftJoin('common_details', 'common_details.id', '=', 'gym_membership_payments.detail_id')
        ->leftJoin('gym_client_purchases', 'gym_client_purchases.id', '=', 'gym_membership_payments.purchase_id')
        ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'gym_client_purchases.membership_id')
        ->select('common_details.title as company','gym_clients.first_name','gym_clients.email', 'gym_clients.mobile','gym_clients.middle_name', 'gym_clients.last_name',
            'gym_membership_payments.payment_amount as paid_amount','gym_memberships.title as membership')
        ->find($payment->id)->toArray();

        $temp = Template::businessTemplateMessage($this->data['user']->detail_id,'payment');
        $message = $temp->renderSMS($temp->message,$data);
        $sms = new CustomerSms(array(
            'message' => $message,
            'status' => 0,
            'phone' => $data['mobile']
        ));
        $sms->recipient_id = $payment->user_id;
        $sms->sender_id = $this->data['user']->id;
        $sms->save();
        $job = new SendCustomerSms($sms);
        $this->dispatch($job);
    }

    public function sendEmail($clientID,$payment){
        try {
            Notification::send(GymClient::find($clientID), new AddPaymentNotification($payment));
        } catch (\Exception $e) {

        }
    }
}

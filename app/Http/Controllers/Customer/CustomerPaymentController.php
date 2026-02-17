<?php

namespace App\Http\Controllers\Customer;

use App\Classes\Reply;
use App\Models\GymClient;
use App\Models\GymInvoice;
use App\Models\GymInvoiceItems;
use App\Models\GymMembership;
use App\Models\GymMembershipPayment;
use App\Models\GymPurchase;
use App\Models\GymSetting;
use App\Models\Merchant;
use App\Notifications\AddPaymentNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CustomerPaymentController extends CustomerBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['paymentMenu']    = 'active';
        $this->data['paymentSubMenu'] = 'active';

        return view('customer-app.payments.index', $this->data);
    }

    public function remainingPayment($id)
    {
        $purchaseId = $id;
        $purchase   = GymPurchase::find($purchaseId);
        return ($purchase->amount_to_be_paid - $purchase->paid_amount);
    }

    public function create($id = null)
    {
        $this->data['paymentMenu']    = 'active';
        $this->data['paymentSubMenu'] = 'active';

        if (!is_null($id)) {
            $this->data['purchases'] = GymPurchase::where('id', $id)->get();
        } else {
            $this->data['purchases'] = GymPurchase::where('client_id', $this->data['customerValues']->id)->get();
        }

        if (is_null($this->data['purchases'])) {
            return App::abort(401);
        }
        return view('customer-app.payments.create', $this->data);
    }

    public function store(Request $request)
    {
        $payment                 = new GymMembershipPayment();
        $payment->user_id        = $this->data['customerValues']->id;
        $payment->payment_amount = $request->get('amount');
        $payment->purchase_id    = $request->get('pid');
        $payment->payment_source = "khalti";
        $payment->remarks        = "Pay by Khalti amt ".$request->amount;
        $payment->payment_date   = Carbon::today()->format('Y-m-d');
        $payment->detail_id      = $this->data['customerValues']->detail_id;
        $payment->payment_id = 'P' . rand(1000,9999);

        // Update the details of next payment in gym_client_purchases
        $purchase                 = GymPurchase::find($request->get('pid'));
        $purchase->paid_amount      = $purchase->paid_amount  + $request->get('amount');
        if ($request->get('amount') >= $purchase->amount_to_be_paid ) {
            $payment_required = "no";
        }else{
            $payment_required = "yes";
        }
        if ($payment_required == "no") {
            $purchase->next_payment_date = null;
        } else {
            $purchase->next_payment_date = Carbon::now()->addDays(7)->format('Y-m-d');
        }
        $purchase->payment_required      = $payment_required;
        $purchase->update();
        $payment->save();
        try {
            Notification::send(GymClient::find($this->data['customerValues']->id), new AddPaymentNotification($payment));
        } catch (\Exception $e) {

        }
        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'redirect_url' => route('customer-app.payments.index'),
                'message' => 'Payment Added Successfully'
            ]);
        } else {
            return Reply::redirect(route('customer-app.payments.index'), 'Payment Added Successfully');
        }
    }

    public function pay(Request $request)
    {
        $this->data['paymentMenu']    = 'active';
        $this->data['paymentSubMenu'] = 'active';
        $validator                    = Validator::make($request->all(), [
            'purchase_id'      => 'required',
            'payment_amount'   => 'required',
            'payment_source'   => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $purchase_id    = $request->get('purchase_id');
        $purchases      = GymPurchase::find($purchase_id);
        $remain = $purchases->amount_to_be_paid - $purchases->paid_amount;
        if($request->get('payment_amount') > $remain){
            return redirect()->back()->withErrors(['payment_amount' => 'Remaining amount is '.$remain]);
        }

        $payment_method = $request->get('payment_source');

        if ($payment_method == 'esewa') {
            $request->session()->put('payment_source', 'esewa');
            $parameters         = [
                'amt'   => $request->get('payment_amount'),
                'pdc'   => 0,
                'psc'   => 0,
                'txAmt' => 0,
                'tAmt'  => $request->get('payment_amount'),
                'purchaseId'  => $purchase_id,
                'pid'   => uniqid(),
                'scd'  => $this->data['gymSettings']->esewa_merchant_id,
                'su'    => route('customer-app.payments.success', $purchase_id),
                'fu'    => route('customer-app.payments.cancel',$purchase_id),
                'type' => 'membership',
            ];
            $this->data['data'] = $parameters;
            return view('customer-app.paymentGateways.esewa', $this->data);
        }

        if ($payment_method == 'khalti') {
            $request->session()->put('payment_source', 'khalti');
            $parameters         = [
                'public'            => $this->data['gymSettings']->khalti_public_key,
                'type'              => 'membership',
                'amt'               => $request->get('payment_amount'),
                'pid'               => $purchases->id,
                'pname'             => $purchases->membership->title,
                'purl'              => 'http://gameofthrones.wikia.com/wiki/Dragons',
            ];
            $this->data['data'] = $parameters;
            return view('customer-app.paymentGateways.khalti', $this->data);
        }

    }

    public function successfulPayment(GymPurchase $purchaseid,Request $request)
    {
        $payment_method = Session::get('payment_source');
        if(is_null($payment_method))
        {
            return Reply::redirect(route('customer-app.payments.create',$purchaseid->id),
                'Payment Source Not Found');
        }
        switch ($payment_method) {
            case 'esewa':
                try {
                    $url  = "https://uat.esewa.com.np/epay/transrec";
                    $data = [
                        'amt' => $request->amt,
                        'rid' => $request->refId,
                        'pid' => $request->oid,
                        'scd' => $this->data['gymSettings']->esewa_merchant_id,
                    ];

                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($curl);
                    $code     = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                    curl_close($curl);

                    if ($code == 200) {
                        $payment                 = new GymMembershipPayment();
                        $payment->user_id        = $this->data['customerValues']->id;
                        $payment->payment_amount = $request->get('amt');
                        $payment->purchase_id    = $purchaseid->id;
                        $payment->payment_source = "esewa";
                        $payment->remarks        = "Pay by Esewa amt ".$request->amt;
                        $payment->payment_date   = Carbon::today()->format('Y-m-d');
                        $payment->detail_id      = $this->data['customerValues']->detail_id;
                        $payment->payment_id = 'P' . random_int(10,99);

                        // Update the details of next payment in gym_client_purchases
                        $payment_required = 'yes';
                        $purchase                 = GymPurchase::find($purchaseid->id);
                        $purchase->paid_amount      = $purchase->paid_amount  + $request->get('amt');
                        if ($request->get('amt') >= $purchase->amount_to_be_paid ) {
                            $payment_required = "no";
                        }else{
                            $payment_required = "yes";
                        }
                        if ($payment_required == "no") {
                            $purchase->next_payment_date = null;
                        } else {
                            $purchase->next_payment_date = Carbon::now()->addDays(7)->format('Y-m-d');
                        }
                        $purchase->payment_required      = $payment_required;
                        $purchase->update();
                        $payment->save();
                        try {
                            Notification::send(GymClient::find($this->data['customerValues']->id), new AddPaymentNotification($payment));
                        } catch (\Exception $e) {

                        }
                        return Reply::redirect(route('customer-app.payments.index'), 'Payment Added Successfully');
                    }
                } catch (Exception $exception) {
                    return Reply::redirect(route('customer-app.payments.index'),
                        'Payment Transaction Cancel. '.$exception->getMessage());
                }
            break;

            case 'khalti':
                try {

                    $url        = "https://khalti.com/api/v2/payment/verify/";
                    $secret_key = $this->data['gymSettings']->khalti_secret_key;

                    $args = http_build_query(array(
                        'token'  => $request->token,
                        'amount' => $request->amount
                    ));

                    # Make the call using API.
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $headers = ["Authorization: Key $secret_key"];
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                    // Response
                    $response    = curl_exec($ch);
                    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);
                    if ($status_code == 200) {
                        return $response;
                    } else {
                        return curl_errno($ch);
                    }

                } catch (Exception $e) {
                    return Reply::redirect(route('customer-app.payments.index'),
                        'Payment Transaction Cancel. '.$e->getMessage());
                }
        }
        return Reply::redirect(route('customer-app.payments.create',$purchaseid->id),
            'Payment Source Not Found');
    }

    public function cancelledPayment(GymPurchase $purchaseid, Request $request)
    {
        $payment_method = Session::get('payment_source');
        switch ($payment_method) {
            case 'esewa' | 'khalti':
                return Reply::redirect(route('customer-app.payments.index'),
                    'Payment Transaction Cancel.');
        }
        return Reply::redirect(route('customer-app.payments.index'),
            'Payment Transaction Cancel.');

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function duePayments()
    {
        $this->data['paymentMenu']    = 'active';
        $this->data['duePaymentMenu'] = 'active';
        $this->data['paymentSubMenu'] = 'inactive';
        return view('customer-app.payments.due-payment', $this->data);
    }

    /**
     * @return mixed
     */
    public function getPaymentData()
    {
        $payments = GymMembershipPayment::select('gym_membership_payments.id as pid', 'gym_clients.first_name','gym_clients.middle_name', 'gym_clients.last_name', 'payment_amount', 'gym_memberships.title as membership', 'payment_source', 'payment_date', 'payment_id','purchase_id')
            ->leftJoin('gym_client_purchases', 'gym_client_purchases.id', '=', 'gym_membership_payments.purchase_id')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'gym_membership_payments.user_id')
            ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'gym_client_purchases.membership_id')
            ->where('gym_membership_payments.user_id', $this->data['customerValues']->id)
            ->orderBy('pid', 'desc')
            ->get();

        return Datatables::of($payments)->editColumn('first_name', function ($row) {
            return ucwords($row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name);
        })->editColumn('payment_source', function ($row) {
            return getPaymentType($row->payment_source);
        })->editColumn('payment_date', function ($row) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $row->payment_date)->toFormattedDateString();
        })->editColumn('payment_amount', function ($row) {
            return $this->data['gymSettings']->currency->acronym . ' ' . $row->payment_amount;
        })->editColumn('payment_id', function ($row) {
            return '<b>' . $row->payment_id . '</b>';
        })->editColumn('payment_type', function ($row) {
            return $row->membership ?? '';
        })->addColumn('action', function ($row) {
            return '<a href="' . route('customer-app.payments.download-invoice', [$row->purchase_id]) . '" class="btn btn-sm btn-info waves-effect">Download Invoice</button>';
        })->removeColumn('last_name')
            ->removeColumn('pid')
            ->removeColumn('package')
            ->removeColumn('payment_frequency')
            ->removeColumn('purchase_id')
            ->removeColumn('membership')
            ->rawColumns(['action','payment_id','payment_source'])
            ->make(true);
    }

    /**
     * @return mixed
     */
    public function getDuePaymentData()
    {
        $purchase = GymPurchase::select('gym_client_purchases.amount_to_be_paid as amount_to_be_paid', 'gym_client_purchases.paid_amount as paid_amount', 'gym_client_purchases.discount as discount', 'next_payment_date as due_date', 'gym_memberships.title as membership', 'gym_client_purchases.id')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'client_id')
            ->leftJoin('gym_memberships', 'gym_memberships.id', '=', 'membership_id')
            ->where('gym_client_purchases.payment_required', 'yes')
            ->where('gym_client_purchases.status', '=', 'active')
            ->where('gym_client_purchases.client_id', $this->data['customerValues']->id)
            ->get();

        return Datatables::of($purchase)
            ->addColumn('gym_clients.membership', function ($row) {
                return $row->membership;
            })
            ->editColumn('purchase_amount', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . $row->amount_to_be_paid;
            })
            ->editColumn('remaining_amount', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . ($row->amount_to_be_paid - $row->paid_amount);
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
                if ($this->data['gymSettings']->payment_status == "enabled") {
                    return '<a href="' . route('customer-app.payments.create', $row->id) . '" class="btn btn-sm btn-info waves-effect">Add Payment</button>';
                } else {
                    return '';
                }
            })
            ->rawColumns(['due_date', 'action'])
            ->make(true);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function downloadInvoice($id)
    {
        $purchase      = GymPurchase::find($id);
        $membership    = GymMembership::find($purchase->membership_id);
        $clientDetails = GymClient::find($purchase->client_id);
        $merchant      = Merchant::where('is_admin', '=', 1)->first();

        $invoiceId = $this->saveInvoice($merchant, $purchase, $clientDetails);
        $this->saveInvoiceItems($invoiceId, $membership, $purchase);

        header('Content-type: application/pdf');

        $this->data['invoice']  = GymInvoice::byInvoiceId($invoiceId, $this->data['customerValues']->detail_id);
        $this->data['settings'] = GymSetting::GetMerchantInfo($this->data['customerValues']->detail_id);

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('customer-app.payments.invoice', $this->data);
        $filename = $this->data['customerBusiness']->business->slug . '-' . $this->data['invoice']->invoice_number;
        if ($this->data['isPhone'])
            return $pdf->stream();
        else
            return $pdf->download($filename . '.pdf');
    }

    /**
     * @param $merchant
     * @param $purchase
     * @param $clientDetails
     * @return mixed
     */
    public function saveInvoice($merchant, $purchase, $clientDetails)
    {
        $name = ucfirst($clientDetails->first_name).' ';
        if(!is_null($clientDetails->middle_name)) {
            $name .= ucfirst($clientDetails->middle_name).' ';
        }
        $name .= ucfirst($clientDetails->last_name);
        $invoice                  = new GymInvoice();
        $invoice->merchant_id     = $merchant->id;
        $invoice->detail_id       = $purchase->detail_id;
        $invoice->client_name     = $name;
        $invoice->client_address  = $clientDetails->address ?? '';
        $invoice->email           = $clientDetails->email;
        $invoice->mobile          = $clientDetails->mobile;
        $invoice->invoice_date    = Carbon::now()->format('Y-m-d');
        $invoice->sub_total       = $purchase->paid_amount;
        $invoice->discount_amount = $purchase->discount;
        $invoice->total           = $purchase->paid_amount;
        $invoice->generated_by    = $name;
        $invoice->save();

        $invoice->invoice_number = strtoupper(Str::random(5)) . $invoice->id;
        $invoice->save();

        return $invoice->id;
    }

    /**
     * @param $invoiceId
     * @param $membership
     * @param $purchase
     */
    public function saveInvoiceItems($invoiceId, $membership, $purchase)
    {
        $invoiceItems                = new GymInvoiceItems();
        $invoiceItems->invoice_id    = $invoiceId;
        $invoiceItems->item_type     = 'item';
        $invoiceItems->item_name     = $membership->title;
        $invoiceItems->quantity      = 1;
        $invoiceItems->cost_per_item = $purchase->paid_amount;
        $invoiceItems->amount        = $purchase->paid_amount;
        $invoiceItems->save();
    }


}

<?php

namespace App\Http\Controllers\Customer;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use App\Models\GymClient;
use App\Models\LockerPayment;
use App\Models\LockerReservation;
use App\Notifications\AddPaymentNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Exception;

class LockerManagementController extends CustomerBaseController
{
    //Locker Payment
    public function index()
    {
        $this->data['lockerMenu'] = 'active';
        $this->data['lockerPaymentSubMenu'] = 'active';
        return view('customer-app.lockers.payments.index', $this->data);
    }

    public function getPaymentData()
    {
        $payments = LockerPayment::where('client_id', $this->data['customerValues']->id)
            ->where('detail_id', $this->data['customerValues']->detail_id)
            ->get();
        return Datatables::of($payments)
            ->addColumn('locker', function ($row) {
                return $row->reservation->locker->locker_num ?? '';
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
                return $row->payment_id ;
            })
            ->rawColumns(['payment_source','locker','payment_date','deleted_at'])
            ->make(true);

    }

    public function create($id = null)
    {
        $this->data['lockerMenu'] = 'active';
        $this->data['lockerPaymentMenu'] = 'active';

        if(!is_null($id)){
            $this->data['purchases'] = LockerReservation::where('payment_required','yes')
                ->where('uuid',$id)->get();
        }else{
            $this->data['purchases'] = LockerReservation::where('payment_required','yes')
                ->where('client_id', $this->data['customerValues']->id)->get();
        }

        if (is_null($this->data['purchases'])) {
            return App::abort(401);
        }
        return view('customer-app.lockers.payments.create', $this->data);
    }

    public function store(Request $request)
    {
        $purchase                   = LockerReservation::find($request->get('pid'));
        $remain = $purchase->amount_to_be_paid - $purchase->paid_amount;
        if(request()->get('payment_amount') > $remain){
            return Reply::error("Remaining amount is ".$remain);
        }
        $inputData = $request->all();
        $inputData['payment_date']    = Carbon::today()->format('Y-m-d');
        $inputData['reservation_id'] = $request->get('pid');
        $inputData['client_id'] = $this->data['customerValues']->id;
        $inputData['detail_id'] = $this->data['customerValues']->detail_id;
        LockerPayment::create($inputData);

        $payment                  = new LockerPayment();
        $payment->payment_amount  = $request->get('amount');
        $payment->payment_source  = "khalti";
        $payment->payment_date    = Carbon::today()->format('Y-m-d');
        $payment->reservation_id  = $request->get('pid');
        $payment->remarks         = "Locker Pay by Khalti amt ".$request->amount;
        $payment->detail_id       = $this->data['customerValues']->detail_id;

        //Update the details of next payment in locker reservation
        $purchase->paid_amount      = $purchase->paid_amount  + $request->get('amount');
        if ($request->get('amount') >= $purchase->total_amount ) {
            $payment_required = "no";
            $purchase->next_payment_date = null;
        }else{
            $payment_required = "yes";
            $purchase->next_payment_date = Carbon::now()->addDays(7)->format('Y-m-d');

        }
        $purchase->payment_required      = $payment_required;
        $purchase->update();

        $client = GymClient::find($this->data['customerValues']->id);
        try {
            $client->notify(new AddPaymentNotification($payment));
        } catch (\Exception $e) {

        }
        return response()->json([
            'status'=>true, 'redirect_url'=> route('customer-app.locker-payments.index'),'message' => 'Locker Payment Added Successfully'
        ]);
    }

    public function pay(Request $request)
    {
        $this->data['lockerMenu'] = 'active';
        $this->data['lockerPaymentMenu'] = 'active';

        $validator                    = Validator::make($request->all(), [
            'reservation_id'      => 'required',
            'payment_amount'   => 'required',
            'payment_source'   => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $purchase_id  = $request->get('reservation_id');
        $purchases  = LockerReservation::find($purchase_id);
        $remain = $purchases->amount_to_be_paid - $purchases->paid_amount;
        if($request->get('payment_amount') > $remain){
            return redirect()->back()->withErrors(['payment_amount' => 'Remaining amount is '.$remain]);
        }
        $payment_method = $request->get('payment_source');

        if ($payment_method == 'esewa') {
            $request->session()->put('payment_source', 'esewa');
            $parameters = [
                'type'   => 'locker',
                'amt'   => $request->get('payment_amount'),
                'pdc'   => 0,
                'psc'   => 0,
                'txAmt' => 0,
                'tAmt'  => $request->get('payment_amount'),
                'purchaseId'  => $purchase_id,
                'pid'   => uniqid(),
                'scd'  => $this->data['gymSettings']->esewa_merchant_id,
                'su'    => route('customer-app.locker-payments.success', $purchase_id),
                'fu'    => route('customer-app.locker-payments.cancel',$purchase_id),
            ];
            $this->data['data'] = $parameters;
            return view('customer-app.paymentGateways.esewa', $this->data);
        }

        if ($payment_method == 'khalti') {
            $request->session()->put('payment_source', 'khalti');
            $parameters = [
                'public'            => $this->data['gymSettings']->khalti_public_key,
                'type'              => 'locker',
                'amt'               => $request->get('payment_amount'),
                'pid'               => $purchases->id,
                'pname'             => $purchases->locker->locker_num,
                'purl'              => 'http://gameofthrones.wikia.com/wiki/Dragons',
            ];
            $this->data['data'] = $parameters;
            return view('customer-app.paymentGateways.khalti', $this->data);
        }

    }

    public function successfulPayment(LockerReservation $reservationId,Request $request)
    {
        $payment_method = Session::get('payment_source');
        if(is_null($payment_method))
        {
            return Reply::redirect(route('customer-app.locker-payments.create',$reservationId->id),
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
                        $payment                  = new LockerPayment();
                        $payment->client_id         = $this->data['customerValues']->id;
                        $payment->payment_amount  = $request->get('amt');
                        $payment->payment_source  = "esewa";
                        $payment->payment_date    = Carbon::today()->format('Y-m-d');
                        $payment->reservation_id  = $reservationId->id;
                        $payment->remarks         = "Locker Pay by Esewa amt ".$request->amt;
                        $payment->detail_id       = $this->data['customerValues']->detail_id;

                        //Update the details of next payment in gym_client_purchases
                        $purchase                   = LockerReservation::find($reservationId->id);
                        $purchase->paid_amount      = $purchase->paid_amount  + $request->get('amt');

                        if ($request->get('amt') >= $purchase->total_amount ) {
                            $payment_required = "no";
                            $purchase->next_payment_date = null;
                        }else{
                            $payment_required = "yes";
                            $purchase->next_payment_date = Carbon::now()->addDays(7)->format('Y-m-d');
                        }
                        $purchase->payment_required      = $payment_required;
                        $purchase->update();
                        $payment->save();
                        $client = GymClient::find($this->data['customerValues']->id);
                        try {
                            $client->notify(new AddPaymentNotification($payment));
                        } catch (\Exception $e) {

                        }
                        return Reply::redirect(route('customer-app.locker-payments.index'), 'Locker Payment Added Successfully');
                    }
                } catch (Exception $exception) {
                    return Reply::redirect(route('customer-app.product-payments.index'),
                        'Product Payment Transaction Cancel. '.$exception->getMessage());
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
                    }
                } catch (Exception $e) {
                    return Reply::redirect(route('customer-app.locker-payments.index'),
                        'Locker Payment Transaction Cancel. '.$e->getMessage());
                }
        }
        return Reply::redirect(route('customer-app.locker-payments.create',$reservationId->id),
            'Payment Source Not Found');
    }

    public function cancelledPayment(LockerReservation $reservationId, Request $request)
    {
        $payment_method = Session::get('payment_source');
        switch ($payment_method) {
            case 'esewa' | 'khalti':
                return Reply::redirect(route('customer-app.locker-payments.index'),
                    'Payment Transaction Cancel.');
        }
        return Reply::redirect(route('customer-app.locker-payments.index'),
            'Payment Transaction Cancel.');

    }

    public function remainingPayment($id)
    {
        $purchase   = LockerReservation::find($id);
        return ($purchase->amount_to_be_paid - $purchase->paid_amount);
    }

    //Locker Due
    public function dueIndex()
    {
        $this->data['lockerMenu'] = 'active';
        $this->data['duePaymentMenu'] = 'active';
        return view('customer-app.lockers.payments.due-payment', $this->data);
    }

    public function getDueData()
    {
        $reservations = LockerReservation::where('payment_required', 'yes')
            ->where('client_id', $this->data['customerValues']->id)
            ->where('detail_id', $this->data['customerValues']->detail_id)
            ->get();

        return Datatables::of($reservations)
            ->editColumn('locker_id', function ($row) {
                return $row->locker->lockerCategory->title .'<br>('.$row->locker->locker_num.')' ?? '';
            })
            ->editColumn('start_date', function ($row) {
                return $row->start_date->toFormattedDateString();
            })
            ->editColumn('amount_to_be_paid', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . $row->amount_to_be_paid;
            })
            ->editColumn('paid_amount', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . $row->paid_amount;
            })
            ->addColumn('remain_amt', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . ($row->amount_to_be_paid - $row->paid_amount);
            })
            ->editColumn('next_payment_date', function ($row) {
                if (isset($row->next_payment_date)) {
                    return $row->next_payment_date->toFormattedDateString() ;
                } else {
                    return 'No due - date';
                }

            })
            ->addColumn('action', function ($row) {
                return '<a href="'.route('customer-app.locker-payments.create',$row->uuid).'" class="btn btn-sm btn-info waves-effect">Add Payment</button>';
            })
            ->rawColumns(['action','next_payment_date','remain_amt','locker_id','start_date'])
            ->make(true);
    }

}

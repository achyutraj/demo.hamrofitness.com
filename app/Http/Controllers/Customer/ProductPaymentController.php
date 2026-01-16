<?php

namespace App\Http\Controllers\Customer;

use App\Classes\Reply;
use App\Models\GymClient;
use App\Models\Product;
use App\Models\ProductPayment;
use App\Models\ProductSales;
use App\Notifications\AddPaymentNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Exception;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ProductPaymentController extends CustomerBaseController
{
    public function index()
    {
        $this->data['productPaymentMenu'] = 'active';
        $this->data['productPaymentSubMenu'] = 'active';

        return view('customer-app.product-payments.index', $this->data);
    }

    public function create($id = null)
    {
        $this->data['productPaymentMenu'] = 'active';
        $this->data['productPaymentSubMenu'] = 'active';

        if(!is_null($id)){
            $this->data['purchases'] = ProductSales::where('payment_required','yes')->where('id',$id)->get();
        }else{
            $this->data['purchases'] = ProductSales::where('payment_required','yes')->where('client_id', $this->data['customerValues']->id)->get();
        }

        if (is_null($this->data['purchases'])) {
            return App::abort(401);
        }
        return view('customer-app.product-payments.create', $this->data);
    }

    public function store(Request $request)
    {
        $payment                  = new ProductPayment();
        $payment->user_id         = $this->data['customerValues']->id;
        $payment->payment_amount  = $request->get('amount');
        $payment->payment_source  = "khalti";
        $payment->payment_date    = Carbon::today()->format('Y-m-d');
        $payment->product_sale_id = $request->get('pid');
        $payment->remarks         = "Product Pay by Khalti amt ".$request->amount;
        $payment->branch_id       = $this->data['customerValues']->detail_id;
        $payment->payment_id = 'HPR' .random_int(10,99);

        //Update the details of next payment in gym_client_purchases
        $purchase                   = ProductSales::find($request->get('pid'));
        $purchase->paid_amount      = $purchase->paid_amount  + $request->get('amount');
        if ($request->get('amount') >= $purchase->total_amount ) {
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
        return response()->json([
            'status'=>true, 'redirect_url'=> route('customer-app.product-payments.index'),'message' => 'Product Payment Added Successfully'
        ]);
    }

    public function pay(Request $request)
    {
        $this->data['productPaymentMenu'] = 'active';
        $this->data['productPaymentSubMenu'] = 'active';

        $validator                    = Validator::make($request->all(), [
            'product_sale_id'      => 'required',
            'payment_amount'   => 'required',
            'payment_source'   => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $purchase_id  = $request->get('product_sale_id');
        $purchases  = ProductSales::find($purchase_id);
        $remain = $purchases->total_amount - $purchases->paid_amount;
        if($request->get('payment_amount') > $remain){
            return redirect()->back()->withErrors(['payment_amount' => 'Remaining amount is '.$remain]);
        }
        $payment_method = $request->get('payment_source');

        if ($payment_method == 'esewa') {
            $request->session()->put('payment_source', 'esewa');
            $parameters = [
                'type'   => 'product',
                'amt'   => $request->get('payment_amount'),
                'pdc'   => 0,
                'psc'   => 0,
                'txAmt' => 0,
                'tAmt'  => $request->get('payment_amount'),
                'purchaseId'  => $purchase_id,
                'pid'   => uniqid(),
                'scd'  => $this->data['gymSettings']->esewa_merchant_id,
                'su'    => route('customer-app.product-payments.success', $purchase_id),
                'fu'    => route('customer-app.product-payments.cancel',$purchase_id),
            ];
            $this->data['data'] = $parameters;
            return view('customer-app.paymentGateways.esewa', $this->data);
        }

        if ($payment_method == 'khalti') {
            $request->session()->put('payment_source', 'khalti');
            $parameters = [
                'public'            => $this->data['gymSettings']->khalti_public_key,
                'type'              => 'product',
                'amt'               => $request->get('payment_amount'),
                'pid'               => $purchases->id,
                'pname'             => $purchases->product_name,
                'purl'              => 'http://gameofthrones.wikia.com/wiki/Dragons',
            ];
            $this->data['data'] = $parameters;
            return view('customer-app.paymentGateways.khalti', $this->data);
        }

    }

    public function successfulPayment(ProductSales $saleid,Request $request)
    {
        $payment_method = Session::get('payment_source');
        if(is_null($payment_method))
        {
            return Reply::redirect(route('customer-app.product-payments.create',$saleid->id),
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
                        $payment                  = new ProductPayment();
                        $payment->user_id         = $this->data['customerValues']->id;
                        $payment->payment_amount  = $request->get('amt');
                        $payment->payment_source  = "esewa";
                        $payment->payment_date    = Carbon::today()->format('Y-m-d');
                        $payment->product_sale_id = $saleid->id;
                        $payment->remarks         = "Product Pay by Esewa amt ".$request->amt;
                        $payment->branch_id       = $this->data['customerValues']->detail_id;
                        $payment->payment_id = 'HPR' .random_int(10,99);

                        //Update the details of next payment in gym_client_purchases
                        $purchase                   = ProductSales::find($saleid->id);
                        $purchase->paid_amount      = $purchase->paid_amount  + $request->get('amt');

                        if ($request->get('amt') >= $purchase->total_amount ) {
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
                        return Reply::redirect(route('customer-app.payments.index'), 'Product Payment Added Successfully');
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
                    return Reply::redirect(route('customer-app.product-payments.index'),
                        'Product Payment Transaction Cancel. '.$e->getMessage());
                }
        }
        return Reply::redirect(route('customer-app.product-payments.create',$saleid->id),
            'Payment Source Not Found');
    }

    public function cancelledPayment(ProductSales $saleid, Request $request)
    {
        $payment_method = Session::get('payment_source');
        switch ($payment_method) {
            case 'esewa' | 'khalti':
                return Reply::redirect(route('customer-app.product-payments.index'),
                    'Payment Transaction Cancel.');
        }
        return Reply::redirect(route('customer-app.product-payments.index'),
            'Payment Transaction Cancel.');

    }

    public function dueIndex()
    {
        $this->data['productPaymentMenu'] = 'active';
        $this->data['productDuePaymentMenu'] = 'active';
        $this->data['productPaymentSubMenu'] = 'inactive';

        return view('customer-app.product-payments.due-payment', $this->data);
    }

    public function remainingPayment($id)
    {
        $purchase   = ProductSales::find($id);
        return ($purchase->total_amount - $purchase->paid_amount);
    }
    public function getPaymentData()
    {
        $payments = ProductPayment::select('product_payments.id as pid','product_sales.product_name','payment_amount', 'payment_source', 'payment_date', 'payment_id', 'product_sale_id')
            ->leftJoin('product_sales', 'product_sales.id', '=', 'product_payments.product_sale_id')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'product_payments.user_id')
            ->where('product_payments.user_id', '=', $this->data['customerValues']->id)
            ->where('product_payments.branch_id', '=', $this->data['customerValues']->detail_id)
            ->get();
        return Datatables::of($payments)
            ->editColumn('product_name', function ($row) {
                $data = '';
                $arr['product_name'] = json_decode($row->product_name,true);
                for($i=0; $i < count( $arr['product_name']) ;$i++){
                    $pro = Product::find($arr['product_name'][$i]);
                    if($i == 0){
                        $data = $pro->name;
                    }else{
                        $data = $data.', '.$pro->name;
                    }
                }
                return $data;
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
            ->rawColumns(['product_name','payment_source','payment_id'])
            ->make();
    }

    public function getDueData()
    {
        $data = ProductSales::select('id','product_name', 'created_at', 'product_amount', 'paid_amount','total_amount')
            ->where('payment_required', 'yes')
            ->where('product_sales.client_id', '=', $this->data['customerValues']->id)
            ->where('product_sales.branch_id', '=', $this->data['customerValues']->detail_id)
            ->get();

        return Datatables::of($data)
            ->editColumn('product_name', function ($row) {
                $data = '';
                $arr['product_name'] = json_decode($row->product_name,true);
                for($i=0; $i < count( $arr['product_name']) ;$i++){
                    $pro = Product::find($arr['product_name'][$i]);
                    if($i == 0){
                        $data = $pro->name;
                    }else{
                        $data = $data.', '.$pro->name;
                    }
                }
                return $data;
            })
            ->editColumn('created_at', function ($row) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->toFormattedDateString();
            })
            ->editColumn('product_amount', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . $row->total_amount;
            })
            ->editColumn('paid_amount', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . $row->paid_amount;
            })
            ->editColumn('total_amount', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . ($row->total_amount - $row->paid_amount);
            })
            ->addColumn('action', function ($row) {
                if ($this->data['gymSettings']->payment_status == "enabled") {
                    return '<a href="'.route('customer-app.product-payments.create',$row->id).'" class="btn btn-sm btn-info waves-effect">Add Payment</button>';
                } else {
                    return '';
                }
            })
            ->rawColumns(['product_name','created_at','action'])
            ->make();
    }

}

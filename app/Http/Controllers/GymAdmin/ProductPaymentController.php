<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Models\GymClient;
use App\Models\GymMembershipPayment;
use App\Models\ProductPayment;
use App\Models\ProductSales;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Yajra\Datatables\Datatables;

class ProductPaymentController extends GymAdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['showproductpaymentMenu'] = 'active';
    }

    public function index()
    {
        if (!$this->data['user']->can("view_product_payment")) {
            return App::abort(401);
        }

        $this->data['title'] = 'Payments';
        return View::make('gym-admin.products.payments.index', $this->data);
    }

    public function ajax_create()
    {
        $payments = ProductPayment::select('product_payments.id as pid', 'gym_clients.first_name', 'gym_clients.middle_name','gym_clients.last_name', 'payment_amount',
            'payment_source', 'payment_date', 'payment_id', 'product_sale_id')
            ->leftJoin('gym_clients', 'gym_clients.id', '=', 'product_payments.user_id')
            ->where('product_payments.branch_id',$this->data['user']->detail_id);

            return Datatables::of($payments)
            ->editColumn('gym_clients.first_name', function ($row) {
                return ucwords($row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name);
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
            ->addColumn('action', function ($row) {

                return "<div class=\"btn-group\">
                            <button class=\"btn btn-xs blue dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\" aria-expanded=\"true\"><i class=\"fa fa-gears\"></i><span class=\"hidden-xs\">ACTION</span>
                                <i class=\"fa fa-angle-down\"></i>
                            </button>
                            <ul class=\"dropdown-menu  pull-right\" role=\"menu\">
                                <li>
                                    <a href='" . route("gym-admin.gym-invoice.create-product-payment-invoice", $row->pid) . "'><i class=\"fa fa-file\"></i> Generate Invoice </a>
                                </li>
                                <li>
                                    <a href='" . route("gym-admin.product-payments.edit", $row->pid) . "'><i class=\"fa fa-edit\"></i> Edit </a>
                                </li>
                                <li>
                                    <a class=\"remove-payment\" data-payment-id=\"$row->pid\"  href=\"javascript:;\"><i class=\"fa fa-trash\"></i> Delete </a>
                                </li>
                         </ul>
                        </div>";


            })
            ->rawColumns(['action','payment_source','payment_id'])
            ->make();
    }

    public function ajax_create_deleted()
    {
        $payments = ProductPayment::onlyTrashed()->where('branch_id',$this->data['user']->detail_id);
        return Datatables::of($payments)
            ->editColumn('user_id', function ($row) {
                return $row->client?->fullName ?? '';
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
            ->editColumn('deleted_at', function ($row) {
                return $row->deleted_at->toFormattedDateString();
            })
            ->rawColumns(['user_id','payment_source','payment_date','deleted_at'])
            ->make(true);
    }

    public function userPayCreate($purchaseId)
    {
        if (!$this->data['user']->can("add_product_payment")) {
            return App::abort(401);
        }
        $this->data['title']       = 'Add Product Payment';
        $this->data['userProductSale'] = true;
        $this->data['product_sale'] = ProductSales::findOrFail($purchaseId);
        $this->data['clients']     = GymClient::GetClients($this->data['user']->detail_id)
                    ->where('customer_id',$this->data['product_sale']->client_id)->get();
        if (is_null($this->data['product_sale'])) {
            return App::abort(404);
        }
        $this->data['paymentSources'] = listPaymentType();
        $this->data['amount'] = $this->data['product_sale'] ? $this->data['product_sale']->total_amount : 0;
        return view('gym-admin.products.payments.create', $this->data);
    }

    public function create()
    {
        if (!$this->data['user']->can("add_product_payment")) {
            return App::abort(401);
        }

        $this->data['title']         = 'Add Payment';
        $this->data['paymentSources'] = listPaymentType();
        $this->data['clients']       = GymClient::GetClients($this->data['user']->detail_id)->active()->whereHas('product_sells')->get();
        $this->data['product_sales'] = ProductSales::where('branch_id', '=', $this->data['user']->detail_id)->get();
        return View::make('gym-admin.products.payments.create', $this->data);
    }

    public function addPaymentModal($id)
    {
        $this->data['purchase']    = ProductSales::find($id);
        $this->data['productName'] = json_decode($this->data['purchase']->product_name, true);
        $this->data['paymentSources'] = listPaymentType();
        return view('gym-admin.products.payments.add_payment_modal', $this->data);
    }

    public function ajaxPaymentStore($id)
    {
        $purchase  = ProductSales::find($id);
        $validator = Validator::make(request()->all(), GymMembershipPayment::rules('ajax_add'));

        if ($validator->fails()) {
            return Reply::formErrors($validator);
        } else {
            $remain = $purchase->total_amount - $purchase->paid_amount;
            if(request()->get('payment_amount') > $remain){
                return Reply::error("Remaining amount is ".$remain);
            }
            $payment                  = new ProductPayment();
            $payment->user_id         = $purchase->client_id;
            $payment->payment_amount  = request()->get('payment_amount');
            $payment->product_sale_id = $purchase->id;
            $payment->payment_source  = request()->get('payment_source');
            $payment->payment_date   = Carbon::createFromFormat('m/d/Y', request()->get('payment_date'))->format('Y-m-d');
            $payment->remarks         = request()->get('remark');
            $payment->payment_type    = null;
            $payment->branch_id       = $purchase->branch_id;
            $payment->payment_id = 'HPR' . rand(1000,9999);

            // Update the details of next payment in product_sales
            $purchase->paid_amount      += request()->get('payment_amount');
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
            return Reply::success('Product Payment Added Successfully');
        }
    }

    public function remainingPayment($id)
    {
        $purchaseId = $id;
        $purchase   = ProductSales::find($purchaseId);
        return $purchase->amount_to_be_paid;
    }

    public function productPayment($id)
    {
        $payAmount                  = request()->get('amount') ?? 0;
        $this->data['productSales'] = ProductSales::select('total_amount', 'paid_amount')->where('client_id',$id)->first();
        $paid                       = $this->data['productSales']->paid_amount;
        $amt                        = $this->data['productSales']->total_amount;
        $this->data['payment']      = ($amt - $paid) - $payAmount;
        return $this->data;
    }

    public function productEditPayment($id)
    {
        $payAmount             = request()->get('amount');
        $old_amount            = request()->get('old_amount');
        $this->data['payment'] = ProductSales::select(DB::raw("(amount_to_be_paid - (paid_amount-$old_amount))-$payAmount as 'diff' "))->where('client_id', '=', $id)->first();
        return $this->data;
    }

    public function clientProductPurchases($id)
    {
        $this->data['purchases'] = ProductSales::clientPurchases($id);
        $view                    = view('gym-admin.products.payments.product_purchase_ajax', $this->data)->render();
        return Reply::successWithData('Client product purchases fetched', ['data' => $view]);
    }

    public function store(Request $request)
    {
        $purchase  = ProductSales::find($request->get('product_sale_id'));
        $validator = Validator::make(request()->all(), [
            'client' => 'required',
            'product_sale_id' => 'required',
            'payment_amount' => 'required|numeric|min:1',
            'payment_source' => 'required',
            'payment_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return Reply::formErrors($validator);
        } else {
            $remain = $purchase->total_amount - $purchase->paid_amount;
            if(request()->get('payment_amount') > $remain){
                return Reply::error("Remaining amount is ".$remain);
            }
            $payment                  = new ProductPayment();
            $payment->user_id         = $purchase->client_id;
            $payment->payment_amount  = $request->get('payment_amount');
            $payment->product_sale_id = $purchase->id;
            $payment->payment_source  = $request->get('payment_source');
            $payment->payment_date   = Carbon::createFromFormat('m/d/Y', $request->get('payment_date'))->format('Y-m-d');
            $payment->remarks         = $request->get('remark');
            $payment->branch_id       = $purchase->branch_id;
            $payment->payment_id = 'HPR' . rand(1000,9999);

            // Update the details of next payment in product_sales
            $purchase->paid_amount      += $request->get('payment_amount');
            if ($remain == $request->get('payment_amount')) {
                $purchase->payment_required = "no";
                $purchase->next_payment_date = null;
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
            $payment->save();
            return Reply::redirect(route('gym-admin.product-payments.index'), 'Product Payment Added Successfully');
        }
    }

    public function show($id)
    {

    }

    public function edit($id)
    {
        if (!$this->data['user']->can("edit_product_payment")) {
            return App::abort(401);
        }

        $this->data['title']            = 'Update Product Payment';
        $this->data['clients']          = GymClient::GetClients($this->data['user']->detail_id);
        $this->data['payment']          = ProductPayment::select('product_payments.*', 'product_sales.payment_required', 'product_sales.next_payment_date as next_date')
            ->leftJoin('product_sales', 'product_sales.id', '=', 'product_payments.product_sale_id')
            ->where('product_payments.id', '=', $id)->first();
        $this->data['purchases']        = ProductSales::clientPurchases($this->data['payment']->user_id);
        $purchase                       = ProductSales::find($this->data['payment']->product_sale_id);
        $this->data['remaining_amount'] = ($purchase->total_amount - $purchase->paid_amount);
        $this->data['paymentSources'] = listPaymentType();
        return View::make('gym-admin.products.payments.edit', $this->data);
    }

    public function update($id)
    {
        if (!$this->data['user']->can("edit_product_payment")) {
            return App::abort(401);
        }
        $purchase                   = ProductSales::find(request()->get('product_sale_id'));
        $action = 'membership';
        $validator = Validator::make(request()->all(), ProductPayment::rules($action));
        if ($validator->fails()) {
            return Reply::formErrors($validator);
        } else {
            $remain = $purchase->total_amount - $purchase->paid_amount;
            if(request()->get('payment_amount') > $remain){
                return Reply::error("Remaining amount is ".$remain);
            }
            $payment                  = ProductPayment::find($id);
            $old_amount               = $payment->payment_amount;
            $payment->payment_amount  = request()->get('payment_amount');
            $payment->payment_source  = request()->get('payment_source');
            $payment->payment_date    = Carbon::createFromFormat('m/d/Y', request()->get('payment_date'))->format('Y-m-d');
            $payment->product_sale_id = request()->get('product_sale_id');
            $payment->remarks         = request()->get('remark');
            $payment->payment_type    = request()->get('payment_type');
            $payment->branch_id       = $this->data['user']->detail_id;
            $payment->payment_id      = 'HPR' . rand(1000,9999);
            $payment->save();

            // Update the details of next payment in gym_client_purchases
            $paid_amount                = $purchase->paid_amount - $old_amount;
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

            return Reply::redirect(route('gym-admin.product-payments.index'), 'Product Payment Updated Successfully');
        }
    }

    public function destroy($id, Request $request)
    {
        if (!$this->data['user']->can("delete_product_payment")) {
            return App::abort(401);
        }

        $payment      = ProductPayment::find($id);
        $old_amount   = $payment->payment_amount;
        if ($payment->product_sale_id != null) {
            $purchase              = ProductSales::find($payment->product_sale_id);
            $purchase->paid_amount = $purchase->paid_amount - $old_amount;
            $purchase->payment_required = "yes";
            $purchase->save();
        }
        ProductPayment::find($id)->delete();
        return Reply::success('Product Payment removed successfully');
    }

}

<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Models\GymClient;
use App\Models\GymInvoice;
use App\Models\GymMembership;
use App\Models\GymMembershipPayment;
use App\Models\GymSetting;
use App\Models\ProductPayment;
use App\Models\LockerPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;

use Carbon\Carbon;
use App\Models\GymInvoiceItems;
use App\Models\Product;
use Barryvdh\DomPDF\Facade as PDF;

class GymInvoiceController extends GymAdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['paymentMenu'] = 'active';
        $this->data['invoiceMenu'] = 'active';
    }

    public function index()
    {
        if (!$this->data['user']->can("view_invoice")) {
            return App::abort(401);
        }

        $this->data['title'] = 'Invoices';
        return view('gym-admin.invoice.index', $this->data);
    }

    public function membershipIndex()
    {
        if (!$this->data['user']->can("view_invoice")) {
            return App::abort(401);
        }

        $this->data['title'] = 'Membership Payment Invoices';
        return view('gym-admin.invoice.membership', $this->data);
    }


    public function productIndex()
    {
        if (!$this->data['user']->can("view_invoice")) {
            return App::abort(401);
        }

        $this->data['title'] = 'Product Payment Invoices';
        return view('gym-admin.invoice.product', $this->data);
    }

    public function lockerIndex()
    {
        if (!$this->data['user']->can("view_invoice")) {
            return App::abort(401);
        }

        $this->data['title'] = 'Locker Payment Invoices';
        return view('gym-admin.invoice.locker', $this->data);
    }

    public function create($type = null)
    {
        if (!$this->data['user']->can("view_invoice")) {
            return App::abort(401);
        }
        $invoices = GymInvoice::with('items')
                        ->whereHas('items',function($query) use ($type){
                            return $query->where('item_type',$type);
                        })
                        ->where('detail_id', '=', $this->data['user']->detail_id);
                        
        return Datatables::of($invoices)
            ->addColumn('items_column', function ($row) {
                return $row->items[0]->item_name ?? '';
            })
            ->addColumn('action', function ($row) {
            $str = '<div class="btn-group">
                        <button class="btn green btn-xs dropdown-toggle" type="button" data-toggle="dropdown"> <span class="hidden-xs">Actions</span>
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <li>';
            if ($row->tax == 0) {
                $str .= '<a href="' . route('gym-admin.gym-invoice.generate-payment-invoice', $row->id) . '"><i class="fa fa-search"></i> View </a>';
            } else {
                $str .= '<a href="' . route('gym-admin.gym-invoice.generate-invoice', $row->id) . '"><i class="fa fa-search"></i> View </a>
';
            }
            $str .= '</li>
                        <li>
                            <a href="' . route('gym-admin.gym-invoice.download-invoice', $row->id) . '"><i class="fa fa-download"></i> Download </a>
                        </li>
                        <li>
                            <a href="javascript:;" data-invoice-id="' . $row->id . '" class="remove-invoice"> <i class="fa fa-trash"></i> Delete</a>
                        </li>
                    </ul>
                </div>';
            return $str;
            })
            ->editColumn('invoice_date', function ($row) {
                return $row->invoice_date->format('d M Y');
            })
            ->editColumn('generated_by', function ($row) {
                return $row->generated_by;
            })
            ->editColumn('client_name', function ($row) {
                return $row->client_name;
            })
            ->editColumn('invoice_number', function ($row) {
                return $row->invoice_number ?? '';
            })
            ->editColumn('total', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . round($row->total, 2);
            })
            ->rawColumns(['action', 'items_column'])
            ->make(true);
    }

    public function createInvoice()
    {
        if (!$this->data['user']->can("create_invoice")) {
            return App::abort(401);
        }
        $this->data['clients'] = GymClient::select('*')
            ->leftJoin('business_customers', 'business_customers.customer_id', '=', 'gym_clients.id')
            ->where('gym_clients.is_client','yes')
            ->where('business_customers.detail_id', '=', $this->data['user']->detail_id)->get();

        $this->data['tax'] = GymSetting::select('*')
            ->where('detail_id', '=', $this->data['user']->detail_id)->get();

        $this->data['memberships'] = GymMembership::membershipByBusiness($this->data['user']->detail_id);
        $this->data['type']      = "tax";
        return view('gym-admin.invoice.create_invoice', $this->data);
    }

    public function saveInvoice(Request $request)
    {
        if (!$this->data['user']->can("create_invoice")) {
            return App::abort(401);
        }

        $items           = $request->input('item_name');
        $amount          = $request->input('amount');
        $client_address  = $request->input('client_address');
        $client_name     = $request->input('client_name');
        $clientEmail     = $request->input('email');
        $clientMobile    = $request->input('mobile');
        $cost_per_item   = $request->input('cost_per_item');
        $discount_amount = ($request->input('discount') == null) ? 0 : $request->input('discount');
        $invoice_date    = $request->input('invoice_date');
        $quantity        = $request->input('quantity');
        $sub_total       = $request->input('sub_total');
        $tax             = $request->input('tax') ?: 0;
        $total           = $request->input('total');
        $generatedBy     = $request->input('generated_by');
        $remarks         = $request->input('remarks');

        $validator = Validator::make(request()->all(), GymInvoice::rules('add'));

        if ($validator->fails()) {
            return Reply::formErrors($validator);
        }

        if (trim($items[0]) == '' || trim($items[0]) == '' || trim($cost_per_item[0]) == '') {
            return Reply::error('Add at-least 1 item.');
        }

        foreach ($items as $item) {
            if ($item == '') {
                return Reply::error('Item Name is empty');
            }
        }

        foreach ($quantity as $qty) {
            if (!is_numeric($qty)) {
                return Reply::error('Quantity should be a number.');
            }
        }

        foreach ($cost_per_item as $rate) {
            if (!is_numeric($rate)) {
                return Reply::error('Rate should be a number.');
            }
        }

        if($request->get('type') == 'tax'){
            foreach ($discount_amount as $discount) {
                if ($discount > 100) {
                    return Reply::error('Discount should be less or equal to 100.');
                }
            }
        }

        $invoice                 = new GymInvoice();
        $invoice->merchant_id    = $this->data['user']->id;
        $invoice->detail_id      = $this->data['user']->detail_id;
        $invoice->client_address = $client_address;
        $invoice->client_name    = $client_name;

        if ($clientEmail != '') {
            $invoice->email = $clientEmail;
        }

        if ($clientMobile != '') {
            $invoice->mobile = $clientMobile;
        }

        $invoice->invoice_date   = Carbon::createFromFormat('m/d/Y', $invoice_date)->format('Y-m-d');
        $invoice->sub_total      = $sub_total;
        $invoice->tax            = $tax;
        $invoice->total          = $total;
        $invoice->generated_by   = $generatedBy;
        $invoice->remarks        = $remarks;
        $invoice->save();
        GymInvoice::where('id', $invoice->id)->update(['invoice_number' => strtoupper(Str::random(5)) . $invoice->id]);

        foreach ($items as $key => $item):
            GymInvoiceItems::create(['invoice_id' => $invoice->id, 'item_name' => $item, 'item_type' => $request->get('type'),
                                     'quantity'   => $quantity[$key], 'cost_per_item' => $cost_per_item[$key], 'discount_amount' => $discount_amount[$key], 'amount' => $amount[$key]]);
        endforeach;

        if (($tax) === 0) {
            return Reply::redirect(route('gym-admin.gym-invoice.generate-payment-invoice', $invoice->id));
        } else {
            return Reply::redirect(route('gym-admin.gym-invoice.generate-invoice', $invoice->id));
        }
    }

    public function generateInvoice($id)
    {
        if (!$this->data['user']->can("create_invoice")) {
            return App::abort(401);
        }

        $this->data['title'] = 'Invoices';

        $this->data['emailSetting'] = GymSetting::select('email_status')->where('detail_id', $this->data['user']->detail_id)->get();

        $this->data['tax'] = GymSetting::select('*')
            ->where('detail_id', '=', $this->data['user']->detail_id)->get();

        $this->data['invoice']  = GymInvoice::byInvoiceId($id, $this->data['user']->detail_id);
        $this->data['settings'] = GymSetting::GetMerchantInfo($this->data['user']->detail_id);

        return view('gym-admin.invoice.generate_invoice', $this->data);
    }

    public function generatePaymentInvoice($id)
    {
        if (!$this->data['user']->can("create_invoice")) {
            return App::abort(401);
        }

        $this->data['title'] = 'Invoices';

        $this->data['emailSetting'] = GymSetting::select('email_status')->where('detail_id', $this->data['user']->detail_id)->get();

        $this->data['tax'] = GymSetting::select('*')
            ->where('detail_id', '=', $this->data['user']->detail_id)->get();

        $this->data['invoice']  = GymInvoice::byInvoiceId($id, $this->data['user']->detail_id);
        $this->data['settings'] = GymSetting::GetMerchantInfo($this->data['user']->detail_id);

        return view('gym-admin.invoice.generate_payment_invoice', $this->data);
    }

    public function downloadInvoice($id)
    {
        if (!$this->data['user']->can("view_invoice")) {
            return App::abort(401);
        }

        header('Content-type: application/pdf');

        $this->data['invoice']  = GymInvoice::byInvoiceId($id, $this->data['user']->detail_id);
        $this->data['settings'] = GymSetting::GetMerchantInfo($this->data['user']->detail_id);
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('gym-admin.pdf.invoice', $this->data);
        $filename = $this->data['merchantBusiness']->business->slug . '-' . $this->data['invoice']->invoice_number;

        if ($this->data['isPhone'])
            return $pdf->stream();
        else
            return $pdf->download($filename . '.pdf');

    }

    /**
     * Create Invoice from membership payment
     * 
    */
    public function createPaymentInvoice($id)
    {
        if (!$this->data['user']->can("create_invoice")) {
            return App::abort(401);
        }

        $this->data['title']   = 'Invoices';
        $this->data['payment'] = GymMembershipPayment::find($id);
        $this->data['item_name']     = $this->data['payment']->purchase->membership->title;
        $this->data['item_rate']     = $this->data['payment']->purchase->purchase_amount;
        $this->data['item_price']    = $this->data['payment']->payment_amount;
        $this->data['item_discount'] = $this->data['payment']->purchase->discount;
        $this->data['discount']      = 0;
        $this->data['type']      = "item";
        return view('gym-admin.invoice.create_payment_invoice', $this->data);
    }

    /**
     * Create Invoice from product payment
     * 
    */
    public function createProductPaymentInvoice($id)
    {
        if (!$this->data['user']->can("create_invoice")) {
            return App::abort(401);
        }

        $this->data['title']            = 'Product Payment Invoices';
        $this->data['payment']          = ProductPayment::find($id);
        $product_name      = json_decode($this->data['payment']->product_sale->product_name,true);
        $productNameArray = [];
        foreach ($product_name as $product_id) {
            $product = Product::find($product_id);
            if ($product != null) {
                $name = $product->name;
            } else {
                $name = 'No Product Name';
            }
            $productNameArray[] = $name;
        }
        $this->data['product_name']    = $productNameArray;
        $this->data['product_price']    = json_decode($this->data['payment']->product_sale->product_price,true);
        $this->data['product_quantity'] = json_decode($this->data['payment']->product_sale->product_quantity,true);
        $this->data['product_discount'] = json_decode($this->data['payment']->product_sale->product_discount,true);
        $this->data['product_amount']   = json_decode($this->data['payment']->product_sale->product_amount,true);
        $this->data['total_amount']   =   $this->data['payment']->payment_amount;
        $this->data['discount']      = 0;
        $userPay = $this->data['payment']->payment_amount;
        $paid = [];
        foreach($this->data['product_amount'] as $key => $payment){
            if ($userPay > 0) {
                if ($payment <= $userPay) {
                    // Paid amount is greater than or equal to the product_amt value
                    $actual[] = $payment;
                    $paid[] = $payment;
                    $remain[] = 0;
                    $userPay -= $payment;
                } else {
                    // Paid amount is less than the product_amt value
                    $actual[] = $payment;
                    $paid[] = $userPay;
                    $remain[] = $payment - $userPay;
                    $userPay = 0; // Exhausted the paid amount
                }
            } else {
                // No payment made, keep the original value
                $actual[] = $payment;
                $paid[] = 0;
                $remain[] = $payment;
            }
        }
        $this->data['paid_amount'] = $paid;
        return view('gym-admin.invoice.create_product_payment_invoice', $this->data);
    }
    
    public function saveProductInvoice(Request $request)
    {
        if (!$this->data['user']->can("create_invoice")) {
            return App::abort(401);
        }
        $items           = $request->input('item_name');
        $amount          = $request->input('amount');
        $client_address  = $request->input('client_address');
        $client_name     = $request->input('client_name');
        $clientEmail     = $request->input('email');
        $clientMobile    = $request->input('mobile');
        $cost_per_item   = $request->input('cost_per_item');
        $discount_amount = ($request->input('discount') == null) ? 0 : $request->input('discount');
        $invoice_date    = $request->input('invoice_date');
        $quantity        = $request->input('quantity');
        $sub_total       = $request->input('sub_total');
        $tax             = $request->input('tax') ?: 0;
        $total           = $request->input('total');
        $generatedBy     = $request->input('generated_by');
        $remarks         = $request->input('remarks');

        $validator = Validator::make(request()->all(), GymInvoice::rules('add'));

        if ($validator->fails()) {
            return Reply::formErrors($validator);
        }

        if (trim($items[0]) == '' || trim($items[0]) == '' || trim($cost_per_item[0]) == '') {
            return Reply::error('Add at-least 1 item.');
        }

        foreach ($items as $item) {
            if ($item == '') {
                return Reply::error('Product Name is empty');
            }
        }

        foreach ($quantity as $qty) {
            if (!is_numeric($qty)) {
                return Reply::error('Product Quantity should be a number.');
            }
        }

        foreach ($cost_per_item as $rate) {
            if (!is_numeric($rate)) {
                return Reply::error('Product Price should be a number.');
            }
        }
        $invoice                 = new GymInvoice();
        $invoice->merchant_id    = $this->data['user']->id;
        $invoice->detail_id      = $this->data['user']->detail_id;
        $invoice->client_address = $client_address;
        $invoice->client_name    = $client_name;

        if ($clientEmail != '') {
            $invoice->email = $clientEmail;
        }

        if ($clientMobile != '') {
            $invoice->mobile = $clientMobile;
        }

        $invoice->invoice_date   = Carbon::createFromFormat('m/d/Y', $invoice_date)->format('Y-m-d');
        $invoice->sub_total      = $sub_total;
        $invoice->tax            = $tax;
        $invoice->total          = $total;
        $invoice->generated_by   = $generatedBy;
        $invoice->remarks        = $remarks;
        $invoice->save();
        GymInvoice::where('id', $invoice->id)->update(['invoice_number' => strtoupper(Str::random(5)) . $invoice->id]);

        foreach ($items as $key => $item):
            GymInvoiceItems::create(['invoice_id' => $invoice->id, 'item_name' => $item, 'item_type' => 'product',
                                     'quantity'   => $quantity[$key], 'cost_per_item' => $cost_per_item[$key], 'discount_amount' => $discount_amount[$key], 'amount' => $amount[$key]]);
        endforeach;

        return Reply::redirect(route('gym-admin.gym-invoice.generate-payment-invoice', $invoice->id));

    }

    /**
     * Create Invoice from locker payment
     * 
    */
    public function createLockerPaymentInvoice($id)
    {
        if (!$this->data['user']->can("create_invoice")) {
            return App::abort(401);
        }

        $this->data['title']   = 'Invoices';
        $this->data['payment'] = LockerPayment::findByUid($id);
        $this->data['item_name']     = $this->data['payment']->reservation->locker->locker_num;
        $this->data['item_rate']     = $this->data['payment']->reservation->purchase_amount;
        $this->data['item_price']    = $this->data['payment']->payment_amount;
        $this->data['item_discount'] = $this->data['payment']->reservation->discount;
        $this->data['discount']      = 0;
        $this->data['type']      = "locker";
        return view('gym-admin.invoice.create_payment_invoice', $this->data);
    }

    public function destroy($id, Request $request)
    {
        if (!$this->data['user']->can("delete_invoice")) {
            return App::abort(401);
        }
        if ($request->ajax()) {
            $invoice = GymInvoice::find($id);
            $invoice->items()->delete();
            $invoice->delete();
            return Reply::success('Invoice deleted successfully');
        }

        return Reply::error('Request not Valid');
    }

    public function emailInvoice(Request $request)
    {
        $id = $request->input('invoiceId');

        $this->data['invoice']  = GymInvoice::byInvoiceId($id, $this->data['user']->detail_id);
        $this->data['settings'] = GymSetting::GetMerchantInfo($this->data['user']->detail_id);

        if (trim($request->input('client_email')) == '') {
            return Reply::error("Enter client's email address.");
        }

        if (!filter_var($request->input('client_email'), FILTER_VALIDATE_EMAIL)) {
            return Reply::error("Enter valid email address.");
        }


        $pdf      = app('dompdf.wrapper');
        $files    = $pdf->loadView('gym-admin.pdf.invoice', $this->data)->save('admin/email-attachments/invoices/invoice-' . $this->data['invoice']->invoice_number . '.pdf');
        $filename = $this->data['merchantBusiness']->business->slug . '-' . $this->data['invoice']->invoice_number;


        $email    = $request->input('client_email');
        $eText    = "Please find your invoice in the attachment.";
        $eTitle   = ucwords($this->data['invoice']->business->title) . ' - Invoice #' . $this->data['invoice']->invoice_number;
        $eHeading = ucwords($this->data['invoice']->business->title) . ' - Invoice #' . $this->data['invoice']->invoice_number;


        // For Mail
        $this->emailNotificationAttachment($email, $eText, $eTitle, $eHeading, null, 'admin/email-attachments/invoices/invoice-' . $this->data['invoice']->invoice_number . '.pdf');
        return Reply::success('Invoice sent by email.');
    }

    public function updateGstNumber()
    {
        $validator = Validator::make(request()->all(), GymSetting::rules('update'));

        if ($validator->fails()) {
            return Reply::formErrors($validator);
        }
        $setting        = GymSetting::firstOrNew(['detail_id' => $this->data['user']->detail_id]);
        $setting->gstin = request()->get('gstin');
        $setting->save();
        return Reply::success('GST Number updated');
    }

}

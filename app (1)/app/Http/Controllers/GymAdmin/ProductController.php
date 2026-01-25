<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Http\Requests\GymAdmin\GymProduct\StoreUpdateRequest;
use App\Models\Employ;
use App\Models\GymClient;
use App\Models\GymSetting;
use App\Models\GymSupplier;
use App\Models\MerchantBusiness;
use App\Models\Product;
use App\Models\ProductSales;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use PDF;
use Illuminate\Http\Request;
use DataTables;

class ProductController extends GymAdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['manageMenu'] = 'active';
    }
    //product
    public function index()
    {
        if (!$this->data['user']->can("view_products")) {
            return App::abort(401);
        }
        $this->data['showproductMenu'] = 'active';
        $this->data['title']           = "Products";
        $this->data['products']        = Product::where('branch_id', $this->data['user']->detail_id)->get();
        $this->data['supplier']        = GymSupplier::where('branch_id', $this->data['user']->detail_id)->get();
        return view('gym-admin.products.index', $this->data);
    }

    public function create()
    {
        if (!$this->data['user']->can("add_products")) {
            return App::abort(401);
        }

        $this->data['title'] = "Product Create";
        $this->data['suppliers']        = GymSupplier::where('branch_id', $this->data['user']->detail_id)->get();
        return view('gym-admin.products.create-edit', $this->data);
    }

    public function store(StoreUpdateRequest $request)
    {
        if (!$this->data['user']->can("add_products")) {
            return App::abort(401);
        }
        $exists = Product::where('name',$request->name)
                    ->where('branch_id',$this->data['user']->detail_id)
                    ->count();
        if($exists > 0){
            return Reply::error('Product name already exists');
        }
        $products                = new Product();
        $products->tag           = $request->tag;
        $products->name          = $request->name;
        $products->brand_name    = $request->brand_name;
        $products->branch_id     = $this->data['user']->detail_id;
        $products->supplier_id   = $request->supplier_id;
        $products->quantity      = $request->quantity;
        $products->price         = $request->price;
        $products->purchase_date = $request->purchase_date;
        $products->expire_date   = $request->expiry_date;
        $products->tag           = $request->tag;
        $products->save();

        return Reply::redirect(route('gym-admin.products.index'), "Product created successfully");
    }

    public function edit($id)
    {
        if (!$this->data['user']->can("edit_products")) {
            return App::abort(401);
        }

        $this->data['product'] = Product::findByUid($id);
        $this->data['title'] = "Product Update";
        $this->data['suppliers'] = GymSupplier::where('branch_id',$this->data['user']->detail_id)->get();
        return View::make('gym-admin.products.create-edit', $this->data);
    }

    public function update(StoreUpdateRequest $request, $id)
    {
        if (!$this->data['user']->can("edit_products")) {
            return App::abort(401);
        }
        $exists = Product::where('name',$request->name)
                    ->where('branch_id',$this->data['user']->detail_id)
                    ->where('uuid','!=',$id)->count();
        if($exists > 0){
            return Reply::error('Product name already exists');
        }
        $products                = Product::findByUid($id);
        $products->tag           = $request->get('tag');
        $products->name          = $request->get('name');
        $products->brand_name    = $request->get('brand_name');
        $products->supplier_id   = $request->get('supplier_id');
        $products->quantity      = $request->get('quantity');
        $products->price         = $request->get('price');
        $products->tag           = $request->get('tag');
        $products->purchase_date = $request->get('purchase_date');
        $products->expire_date   = $request->get('expiry_date');
        $products->save();

        return Reply::redirect(route('gym-admin.products.index'), "Product updated successfully");
    }


    public function destroy($id)
    {
        if (!$this->data['user']->can("delete_products")) {
            return App::abort(401);
        }
        $product = Product::findByUid($id);
        $allSales = ProductSales::where('branch_id',$this->data['user']->detail_id)->get();
        $existSale = $allSales->contains(function ($sale) use ($id) {
            $productNames = json_decode($sale->product_name);
            return in_array($id, $productNames);
        });
        if($existSale){
            return redirect()->route('gym-admin.products.index')->with('message', 'Unable to remove. Product has sales');
        }
        $product->delete();
        return redirect()->route('gym-admin.products.index')->with('message', 'Product Deleted Successfully');
    }

    // product sales
    public function saleIndex()
    {
        if (!$this->data['user']->can("view_sells")) {
            return App::abort(401);
        }
        $this->data['showsellMenu']     = 'active';
        $this->data['title']            = "Product Sell";
        $active_products  = Product::whereDate('expire_date', '>', Carbon::now()->format('Y-m-d'))
                            ->where('branch_id', $this->data['user']->detail_id)->get();
        $nondate_products  = Product::WhereNull('expire_date')
                        ->where('branch_id', $this->data['user']->detail_id)->get();
        $this->data['products'] = $active_products->concat($nondate_products);
        $this->data['employees']        = Employ::where('detail_id', $this->data['user']->detail_id)->get();
        $this->data['clients']          = GymClient::join('business_customers', 'business_customers.customer_id', '=', 'gym_clients.id')
                                        ->where('business_customers.detail_id', '=', $this->data['user']->detail_id)
                                        ->where('gym_clients.is_client','yes')
                                        ->orderBy('gym_clients.first_name','asc')->get();
        $this->data['soldProduct']      = ProductSales::where('branch_id', $this->data['user']->detail_id)->get();
        $this->data['settings']         = GymSetting::GetMerchantInfo($this->data['user']->detail_id);
        $this->data['merchantBusiness'] = MerchantBusiness::merchantBusinessDetails($this->data['user']->detail_id);
        $this->data['gymSettings']      = GymSetting::GetMerchantInfo($this->data['user']->detail_id);
        return view('gym-admin.products.sales.index', $this->data);
    }

    public function saleCreate()
    {
        if (!$this->data['user']->can("view_sells")) {
            return App::abort(401);
        }
        $this->data['title']     = "Product Sell";
        $active_products  = Product::whereDate('expire_date', '>', Carbon::now()->format('Y-m-d'))
                            ->where('branch_id', $this->data['user']->detail_id)->get();
        $nondate_products  = Product::WhereNull('expire_date')
                        ->where('branch_id', $this->data['user']->detail_id)->get();
        $this->data['products'] = $active_products->concat($nondate_products);
        $this->data['employees'] = Employ::where('detail_id', $this->data['user']->detail_id)->get();
        $this->data['clients']   = GymClient::join('business_customers', 'business_customers.customer_id', '=', 'gym_clients.id')
            ->active()->where('gym_clients.is_client','yes')
            ->where('business_customers.detail_id', '=', $this->data['user']->detail_id)->orderBy('gym_clients.first_name','asc')->get();
        return view('gym-admin.products.sales.create', $this->data);
    }

    public function saleStore(Request $request)
    {
        if (!$this->data['user']->can("add_sells")) {
            return App::abort(401);
        }
        $validator = Validator::make($request->all(), [
            'product_quantity'   => 'required|array',
            'product_quantity.*' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $product                = new ProductSales();
        $count                  = count($request->product);
        $product->customer_type = $request->customer_type;
        $products               = array();
        if ($request->customer_type == "local") {
            $product->customer_name = $request->customer_name;
        } elseif ($request->customer_type == "employ") {
            $product->employ_id     = $request->customer_name;
            $employ                 = Employ::select('first_name', 'middle_name', 'last_name')->where('id', $request->customer_name)->first();
            $product->customer_name = $employ->first_name . ' ' . $employ->middle_name . ' ' . $employ->last_name;
        } else {
            $product->client_id     = $request->customer_name;
            $client                 = GymClient::select('first_name', 'middle_name', 'last_name')->where('id', $request->customer_name)->first();
            $product->customer_name = $client->first_name . ' ' . $client->middle_name . ' ' . $client->last_name;
        }

        for ($i = 0; $i < $count; $i++) {
            $prod = Product::findorfail($request->product[$i]);
            if ($prod->quantity_sold == 0) {
                $prod->quantity_sold = $request->product_quantity[$i];
            } else {
                $prod->quantity_sold = $prod->quantity_sold + $request->product_quantity[$i];
            }
            $prod->save();
            array_push($products, $prod->id);
        }
        $product->product_name      = json_encode($products);
        $product->product_price     = json_encode($request->product_price);
        $product->product_quantity  = json_encode($request->product_quantity);
        $product->product_discount  = json_encode($request->product_discount);
        $product->product_amount    = json_encode($request->amount);
        $product->total_amount      = array_sum($request->amount);
        $product->paid_amount       = 0;
        $product->payment_required  = 'yes';
        $product->next_payment_date = ($request->next_payment_date != null) ? Carbon::createFromFormat('Y-m-d', $request->next_payment_date) : today()->addDays(2)->format('Y-m-d');
        $product->branch_id         = $this->data['user']->detail_id;
        $product->save();
        return redirect()->route('gym-admin.product-payments.user-create',['purchaseId'=>$product->id])->with('message', 'Product Sale Stored Successfully');
    }

    public function saleEdit($id)
    {
        if (!$this->data['user']->can("edit_sells")) {
            return App::abort(401);
        }
        $this->data['products'] = ProductSales::find($id);
        $this->data['title']    = "Product Edit";
        return view('gym-admin.products.sales.edit', $this->data);
    }

    public function saleUpdate(Request $request, $id)
    {
        if (!$this->data['user']->can("edit_sells")) {
            return App::abort(401);
        }
        $product  = ProductSales::findorfail($id);
        $count    = count($request->product);
        $quantity = json_decode($product->product_quantity);
        for ($i = 0; $i < $count; $i++) {
            $prod                = Product::find($request->product[$i]);
            $prod->quantity_sold = ($prod->quantity_sold - $quantity[$i]) + $request->product_quantity[$i];
            $prod->save();
        }
        $product->customer_type     = $request->customer_type;
        $product->customer_name     = $request->customer_name;
        $product->product_name      = json_encode($request->product);
        $product->product_price     = json_encode($request->product_price);
        $product->product_quantity  = json_encode($request->product_quantity);
        $product->product_discount  = json_encode($request->product_discount);
        $product->product_amount    = json_encode($request->amount);
        $product->total_amount      = array_sum($request->amount);
        $product->payment_required  = 'yes';
        $product->next_payment_date = ($request->next_payment_date != null) ? $request->next_payment_date : null;
        $product->save();
        return redirect()->route('gym-admin.sales.index')->with('message', 'Product Sale Updated Successfully');
    }

    public function saleDelete($id)
    {
        if (!$this->data['user']->can("delete_sells")) {
            return App::abort(401);
        }
        $sales = ProductSales::find($id);
        if($sales->productPayment->count() > 0){
            return redirect()->route('gym-admin.sales.index')->with('message', 'Unable to remove.Sales has some payment.');
        }
        $products = json_decode($sales->product_name,true);
        $quantity = json_decode($sales->product_quantity,true);
        for($i=0; $i < count($products); $i++){
            $prod = Product::find($products[$i]);
            $prod->quantity_sold = $prod->quantity_sold - $quantity[$i];
            $prod->save();
        }
        $sales->delete();
        return redirect()->route('gym-admin.sales.index')->with('message', 'Product Sales Deleted Successfully');
    }

    public function saleDownload($id)
    {
        if (!$this->data['user']->can("view_invoice")) {
            return App::abort(401);
        }
        header('Content-type: application/pdf');
        $this->data['invoice']          = ProductSales::findOrFail($id);
        if($this->data['user']->is_admin == 1){
            $this->data['merchantBusiness'] = MerchantBusiness::merchantBusinessDetails($this->data['user']->id);
        }else{
            $this->data['merchantBusiness'] = MerchantBusiness::merchantBusinessDetails($this->data['user']->detail_id);
        }
        $this->data['gymSettings']      = GymSetting::GetMerchantInfo($this->data['user']->detail_id);
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.product-invoice', $this->data);
        $filename = $this->data['invoice']->customer_name.'-productInvoice.pdf';
        return $pdf->download($filename);
    }

    public function show($id)
    {

    }

    //product dues
    public function productDues()
    {
        if (!$this->data['user']->can("view_due_payments")) {
            return App::abort(401);
        }
        $this->data['account']             = 'active';
        $this->data['showProductDuesMenu'] = 'active';
        $this->data['title']               = 'Product Due';
        $this->data['soldProduct']         = ProductSales::where('branch_id', $this->data['user']->detail_id)->get();

        return View::make('gym-admin.products.dues', $this->data);
    }

    public function ajax_create()
    {
        if (!$this->data['user']->can("view_sells")) {
            return App::abort(401);
        }

        $data = ProductSales::select('product_sales.id', 'customer_name', 'product_name', 'product_sales.created_at', 'product_amount', 'paid_amount',
            'total_amount','next_payment_date')
            ->where('payment_required', 'yes')
            ->where('branch_id', '=', $this->data['user']->detail_id);

        return Datatables::of($data)
            ->editColumn('customer_name', function ($row) {
                return $row->customer_name;
            })
            ->editColumn('product_name', function ($row) {
                $data = '';
                $arr['product_name'] = json_decode($row->product_name,true);
                for($i=0; $i < count( $arr['product_name']) ;$i++){
                    $pro = Product::find($arr['product_name'][$i]);
                    if($pro != null){
                        if($i == 0){
                            $data = $pro->name ?? '';
                        }else{
                            $data = $data.', '.$pro->name ?? '';
                        }
                    }
                }
                return $data;
            })
            ->editColumn('created_at', function ($row) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->toFormattedDateString();
            })
            ->editColumn('product_amount', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' .$row->total_amount;
            })
            ->editColumn('paid_amount', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' .$row->paid_amount;
            })
            ->editColumn('total_amount', function ($row) {
                return $this->data['gymSettings']->currency->acronym . ' ' . ($row->total_amount - $row->paid_amount);
            })
            ->editColumn('next_payment_date', function ($row) {
                if ($row->next_payment_date != null) {
                    return $row->next_payment_date->toFormattedDateString();
                } else {
                    return 'No due - date';
                }
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group">
                    <button class="btn blue btn-xs dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-gears"></i> <span class="hidden-xs hidden-medium">Actions</span>
                        <i class="fa fa-angle-down"></i>
                    </button>
                    <ul class="dropdown-menu pull-right" role="menu">

                        <li>
                            <a class="add-payment" data-id="' . $row->id . '"  href="javascript:;"><i class="fa fa-plus"></i> Add Payment </a>
                        </li>

                    </ul>
                </div>';
            })
            ->rawColumns(['action','product_name'])
            ->make(true);
    }

    public function quantity($id)
    {
        if (!$this->data['user']->can("edit_products")) {
            return App::abort(401);
        }

        $this->data['product'] = Product::findByUid($id);
        $this->data['title'] = "Product Quantity Management";
        return view('gym-admin.products.quantity', $this->data);
    }

    public function updateQuantity(Request $request, $id)
    {
        if (!$this->data['user']->can("edit_products")) {
            return App::abort(401);
        }

        $request->validate([
            'action' => 'required|in:add,remove,update',
            'quantity' => 'required|integer|min:1',
            'notes' => 'required|string'
        ]);

        $product = Product::findByUid($id);

        try {
            switch ($request->action) {
                case 'add':
                    $product->addQuantity($request->quantity, $request->notes);
                    break;
                case 'remove':
                    $product->removeQuantity($request->quantity, $request->notes);
                    break;
                case 'update':
                    $product->updateQuantity($request->quantity, $request->notes);
                    break;
            }

            return Reply::success('Product quantity updated successfully');
        } catch (\Exception $e) {
            return Reply::error('Error while updating quantity: ' . $e->getMessage());
        }
    }
}

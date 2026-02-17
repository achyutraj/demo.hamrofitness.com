<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerApp\Product\StorePaymentRequest;
use App\Http\Resources\Swagger\Product\ProductPaymentResource;
use App\Http\Resources\Swagger\Product\ProductPurchaseResource;
use App\Models\GymClient;
use App\Models\Product;
use App\Models\ProductPayment;
use App\Models\ProductSales;
use App\Notifications\AddPaymentNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Exception;

class ProductPaymentController extends CustomerBaseController
{

    /**
     * @OA\Get(
     *      path="/api/customer/productPayments",
     *      operationId="productPayment",
     *      tags={"Product"},
     *      security={{"passport": {}}},
     *      summary="Customer product payment list",
     *      description="Returns list of customer product payments",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ProductPaymentResource")
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *      )
     * )
    */

    public function index()
    {
        $payments = ProductPayment::where('user_id', $this->getCustomerData()->id)
            ->where('branch_id', $this->getCustomerData()->detail_id)
            ->latest()->paginate(10);

        $new_payments = $payments->getCollection()->map(function ($payment) {
            $purchase = ProductSales::findOrFail($payment->product_sale_id);

            if($purchase == null){
                return $this->sendError('Product Purchase Not Found');
            }

            $productNames = json_decode($purchase->product_name, true);
            $productPrices = json_decode($purchase->product_price, true);
            $productQuantities = json_decode($purchase->product_quantity, true);
            $productDiscounts = json_decode($purchase->product_discount, true);
            $productAmounts = json_decode($purchase->product_amount, true);
        
            $products = [];
            foreach ($productNames as $index => $name) {
                $product = Product::findOrFail($name);
                $products[] = [
                    'name' => $product->name,
                    'price' => $productPrices[$index],
                    'quantity' => $productQuantities[$index],
                    'discount' => $productDiscounts[$index],
                    'amount' => $productAmounts[$index],
                ];
            }
        
            $formattedPurchase = [
                'id' => $purchase->id,
                'products' => $products,
                'total_amount' => $purchase->total_amount,
                'paid_amount' => $purchase->paid_amount,
                'created_at' => $purchase->created_at,
                'payment_required' => $purchase->payment_required,
                'next_payment_date' => $purchase->next_payment_date,
                'status' => $purchase->status,
                'deleted_at' => $purchase->deleted_at,
            ];
    
            $formattedPurchase =  new ProductPurchaseResource((object) $formattedPurchase);

            return (object) [
                'id' => $payment->id,
                'product_sale' => $formattedPurchase,
                'payment_id'=> $payment->payment_id,
                'payment_amount'=> $payment->payment_amount,
                'payment_date'=> $payment->payment_date,
                'payment_source'=> $payment->payment_source,
                'remarks'=> $payment->remarks,
                'deleted_at' => $payment->deleted_at,
            ];
        });

        $paginatedNewPurchases = new \Illuminate\Pagination\LengthAwarePaginator(
            $new_payments,
            $payments->total(),
            $payments->perPage(),
            $payments->currentPage(),
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        return ProductPaymentResource::collection($paginatedNewPurchases);
    }



      /**
     * @OA\Post(
     *      path="/api/customer/productPayments/store",
     *      operationId="customerProductPaymentStore",
     *      tags={"Product"},
     *      security={{"passport": {}}},
     *      summary="Add customer product payment",
     *      description="Returns data of product payment done by customer",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StorePaymentRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ProductPaymentResource")
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *         )
     *     )
    */
    public function store(StorePaymentRequest $request)
    {
        $payment                  = new ProductPayment();
        $payment->user_id         = $this->getCustomerData()->id;
        $payment->payment_amount  = $request->get('payment_amount');
        $payment->payment_source  = $request->get('payment_source');
        $payment->payment_date    = Carbon::today()->format('Y-m-d');
        $payment->product_sale_id = $request->get('product_sale_id');
        $payment->remarks         = $request->get('remarks');
        $payment->branch_id       = $this->getCustomerData()->detail_id;
        $payment->payment_id = 'HPR' .random_int(10,99);

        //Update the details of next payment in gym_client_purchases
        $purchase                   = ProductSales::find($request->get('product_sale_id'));
        $purchase->paid_amount      = $purchase->paid_amount  + $request->get('payment_amount');
        if ($request->get('payment_amount') >= $purchase->total_amount ) {
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
        return new ProductPaymentResource($payment);
    }


    /**
     * @OA\Get(
     *      path="/api/customer/productPayments/due-payments",
     *      operationId="productDuePayment",
     *      tags={"Product"},
     *       security={{"passport": {}}},
     *      summary="Customer product due payment list",
     *      description="Returns list of customer due product payments",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ProductPurchaseResource")
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *         )
     *     )
    */
    public function dueIndex()
    {
        $dues = ProductSales::where('payment_required', 'yes')
            ->where('client_id', $this->getCustomerData()->id)
            ->where('branch_id', $this->getCustomerData()->detail_id)
            ->paginate(10);

        $new_purchases = $dues->getCollection()->map(function ($purchase) {
            $productNames = json_decode($purchase->product_name, true);
            $productPrices = json_decode($purchase->product_price, true);
            $productQuantities = json_decode($purchase->product_quantity, true);
            $productDiscounts = json_decode($purchase->product_discount, true);
            $productAmounts = json_decode($purchase->product_amount, true);

            $products = [];
            foreach ($productNames as $index => $name) {
                $product = Product::findOrFail($name);
                $products[] = [
                    'name' => $product->name,
                    'price' => $productPrices[$index],
                    'quantity' => $productQuantities[$index],
                    'discount' => $productDiscounts[$index],
                    'amount' => $productAmounts[$index],
                ];
            }

            return (object) [
                'id' => $purchase->id,
                'products' => $products,
                'total_amount' => $purchase->total_amount,
                'paid_amount' => $purchase->paid_amount,
                'payment_required' => $purchase->payment_required,
                'next_payment_date' => $purchase->next_payment_date,
                'status' => $purchase->status,
                'deleted_at' => $purchase->deleted_at,
            ];
        });

        $paginatedNewPurchases = new \Illuminate\Pagination\LengthAwarePaginator(
            $new_purchases,
            $dues->total(),
            $dues->perPage(),
            $dues->currentPage(),
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        return ProductPurchaseResource::collection($paginatedNewPurchases);
    }
}

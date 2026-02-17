<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerApp\Product\StoreProductPurchaseRequest;
use App\Http\Resources\Swagger\Product\ProductPurchaseResource;
use App\Http\Resources\Swagger\Product\ProductResource;
use App\Mail\AdminProductPurchaseNotification;
use App\Models\Product;
use App\Models\Merchant;
use App\Models\MerchantNotification;
use App\Models\ProductSales;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CustomerProductPurchaseController extends CustomerBaseController
{
   
     /**
     * @OA\Get(
     *      path="/api/customer/product-lists",
     *      operationId="getProductList",
     *      tags={"Product Purchase"},
     *      security={{"passport": {}}},
     *      summary="Get list of all products",
     *      description="Return listing of products",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ProductResource")
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *         )
     *     )
    */
    public function productLists()
    {
        $products = Product::where('branch_id', $this->getCustomerData()->detail_id); 
        return ProductResource::collection($products->paginate(10));
    }

   /**
     * @OA\Get(
     *      path="/api/customer/product-purchases",
     *      operationId="getProductPurchaseList",
     *      tags={"Product Purchase"},
     *      security={{"passport": {}}},
     *      summary="Get list of all product purchases",
     *      description="Return listing of customer product purchases",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ProductPurchaseResource")
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *      )
     * )
     */
    public function index()
    {
        $purchases = ProductSales::where('client_id', $this->getCustomerData()->id)
            ->orderBy('id', 'desc')
            ->paginate(10);

        $new_purchases = $purchases->getCollection()->map(function ($purchase) {
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
            $purchases->total(),
            $purchases->perPage(),
            $purchases->currentPage(),
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        return ProductPurchaseResource::collection($paginatedNewPurchases);
    }


      /**
     * @OA\Post(
     *      path="/api/customer/product-purchases/store",
     *      operationId="customerProductPurchaseAdd",
     *      tags={"Product Purchase"},
     *      security={{"passport": {}}},
     *      summary="Add customer product purchases",
     *      description="Returns data of product purchases done by customer",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreProductPurchaseRequest")
     *      ),
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
    public function store(StoreProductPurchaseRequest $request)
    {
        $purchase                = new ProductSales();
        $count                  = count($request->product);
        $purchase->customer_type = 'client';
        $products               = array();
        
        $purchase->client_id     = $this->getCustomerData()->id;
        $purchase->customer_name = $this->getCustomerData()->fullName;

        for ($i = 0; $i < $count; $i++) {
            $prod = Product::findOrFail($request->product[$i]);
            if ($prod->quantity_sold == 0) {
                $prod->quantity_sold = $request->product_quantity[$i];
            } else {
                $prod->quantity_sold = $prod->quantity_sold + $request->product_quantity[$i];
            }
            $prod->save();
            array_push($products, $prod->id);
        }
        $purchase->product_name      = json_encode($products);
        $purchase->product_price     = json_encode($request->product_price);
        $purchase->product_quantity  = json_encode($request->product_quantity);
        $purchase->product_discount  = json_encode($request->product_discount);
        $purchase->product_amount    = json_encode($request->amount);
        $purchase->total_amount      = array_sum($request->amount);
        $purchase->paid_amount       = 0;
        $purchase->payment_required  = 'yes';
        $purchase->next_payment_date = today()->addDays(2)->format('Y-m-d');
        $purchase->branch_id         = $this->getCustomerData()->detail_id;
        $purchase->save();
        return new ProductPurchaseResource((object) $purchase);
    }


     /**
     * @OA\Get(
     *      path="/api/customer/product-purchases/show/{id}",
     *      operationId="getProductPurchaseById",
     *      tags={"Product Purchase"},
     *      security={{"passport": {}}},
     *      summary="Get data of product purchases",
     *      description="Return info of product purchases",
     *      @OA\Parameter(
     *          name="id",
     *          description="ProductPurchase id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
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
    public function show($id)
    {
        $purchase = ProductSales::where('id',$id)
                ->where('client_id',$this->getCustomerData()->id)->first();
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
    
        return new ProductPurchaseResource((object) $formattedPurchase);
    }


     /**
     * @OA\Delete(
     *      path="/api/customer/product-purchases/destroy/{id}",
     *      operationId="deleteProductPurchaseById",
     *      tags={"Product Purchase"},
     *      security={{"passport": {}}},
     *      summary="Delete data of product purchases",
     *      description="Remove product purchases",
     *      @OA\Parameter(
     *          name="id",
     *          description="ProductPurchase id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *         )
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *         )
     *     )
    */
    public function destroy($id)
    {
        $purchase =  ProductSales::where('id',$id)
                ->where('client_id',$this->getCustomerData()->id)->first();
        
        if($purchase == null){
            return $this->sendError('Product Purchase Not Found');
        }
        if($purchase->productPayment->count() > 0){
            return $this->sendError('Unable to remove. Product Purchase has some payment.');
        }
        $purchase->delete();
        return $this->sendResponse($purchase,'Product Purchase Delete');
    }

}

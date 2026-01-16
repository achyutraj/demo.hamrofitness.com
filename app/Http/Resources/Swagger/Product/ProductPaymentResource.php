<?php

namespace App\Http\Resources\Swagger\Product;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="ProductPaymentResource",
 *     description="Product Payment resource",
 *     @OA\Xml(
 *         name="ProductPaymentResource"
 *     )
 * )
 */
class ProductPaymentResource extends JsonResource
{
     /**
     * @OA\Property(
     *     title="Data",
     *     description="Data wrapper"
     * )
     *
     * @var \App\Models\ProductPayment
     */
    private $data;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'payment_id' => $this->payment_id,
            'payment_amount' => $this->payment_amount,
            'payment_date' => $this->payment_date,
            'payment_source' => $this->payment_source,
            'remarks' => $this->remarks,
            'product_sale' => new ProductPurchaseResource($this->product_sale),
        ];
    }
}

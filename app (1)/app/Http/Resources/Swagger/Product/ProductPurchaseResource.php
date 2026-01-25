<?php

namespace App\Http\Resources\Swagger\Product;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="ProductPurchaseResource",
 *     description="Product Purchase resource",
 *     @OA\Xml(
 *         name="ProductPurchaseResource"
 *     )
 * )
 */
class ProductPurchaseResource extends JsonResource
{
     /**
     * @OA\Property(
     *     title="Data",
     *     description="Data wrapper"
     * )
     *
     * @var \App\Models\ProductSales
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
            'products' => $this->products,
            'total_amount' => $this->total_amount,
            'paid_amount' => $this->paid_amount,
            'remaining_amount' => $this->total_amount - $this->paid_amount,
            'payment_required' => $this->payment_required,
            'next_payment_date' => $this->next_payment_date,
            'status' => $this->status,
            'deleted_at' => $this->deleted_at,
        ];
    }
}

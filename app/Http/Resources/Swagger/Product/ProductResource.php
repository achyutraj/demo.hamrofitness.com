<?php

namespace App\Http\Resources\Swagger\Product;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="ProductResource",
 *     description="Product resource",
 *     @OA\Xml(
 *         name="ProductResource"
 *     )
 * )
 */
class ProductResource extends JsonResource
{
      /**
     * @OA\Property(
     *     title="Data",
     *     description="Data wrapper"
     * )
     *
     * @var \App\Models\Product
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
            'tag' => $this->tag,
            'name' => $this->name,
            'brand_name' => $this->brand_name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'quantity_expired' => $this->quantity_expired,
            'quantity_damaged' => $this->quantity_damaged,
            'status' => $this->status,
            'purchase_date' => $this->purchase_date,
            'expire_date' => $this->expire_date,
        ];
    }
}

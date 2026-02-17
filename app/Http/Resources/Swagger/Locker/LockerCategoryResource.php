<?php

namespace App\Http\Resources\Swagger\Locker;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="LockerCategoryResource",
 *     description="LockerCategory resource",
 *     @OA\Xml(
 *         name="LockerCategoryResource"
 *     )
 * )
 */

class LockerCategoryResource extends JsonResource
{
     /**
     * @OA\Property(
     *     title="Data",
     *     description="Data wrapper"
     * )
     *
     * @var \App\Models\LockerCategory
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
            'uuid' => $this->uuid,
            'title' => $this->title,
            'type' => $this->type,
            'duration' => $this->duration,
            'duration_type' => $this->duration_type,
            'price' => $this->price,
            'three_month_price' => $this->three_month_price,
            'six_month_price' => $this->six_month_price,
            'one_year_price' => $this->one_year_price,
            'details' => $this->details,
        ];
    }
}

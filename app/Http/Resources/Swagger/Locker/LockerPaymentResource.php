<?php

namespace App\Http\Resources\Swagger\Locker;

use App\Models\LockerPayment;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="LockerPaymentResource",
 *     description="LockerPayment resource",
 *     @OA\Xml(
 *         name="LockerPaymentResource"
 *     )
 * )
 */

class LockerPaymentResource extends JsonResource
{
     /**
     * @OA\Property(
     *     title="Data",
     *     description="Data wrapper"
     * )
     *
     * @var LockerPayment
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
            'payment_id' => $this->payment_id,
            'payment_amount' => $this->payment_amount,
            'payment_date' => $this->payment_date,
            'payment_source' => $this->payment_source,
            'remarks' => $this->remarks,
            'reservation' => new LockerReservationResource($this->reservation),
        ];
    }
}

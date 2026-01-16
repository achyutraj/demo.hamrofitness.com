<?php

namespace App\Http\Resources\Swagger\Locker;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="LockerReservationResource",
 *     description="LockerReservation resource",
 *     @OA\Xml(
 *         name="LockerReservationResource"
 *     )
 * )
 */
class LockerReservationResource extends JsonResource
{
    /**
     * @OA\Property(
     *     title="Data",
     *     description="Data wrapper"
     * )
     *
     * @var \App\Models\LockerReservation
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
            'locker_id' => $this->locker_id,
            'purchase_amount' => $this->purchase_amount,
            'paid_amount' => $this->paid_amount,
            'discount' => $this->discount,
            'purchase_date' => $this->purchase_date,
            'start_date' => $this->start_date,
            'next_payment_date' => $this->next_payment_date,
            'end_date' => $this->end_date,
            'remarks' => $this->remarks,
            'amount_to_be_paid' => $this->amount_to_be_paid,
            'remaining_amount' => $this->amount_to_be_paid - $this->paid_amount,
            'payment_required' => $this->payment_required,
            'status' => $this->status,
            'deleted_at' => $this->deleted_at,
            'is_renew' => $this->is_renew,
            'locker' => new LockerResource($this->locker),
        ];
    }
}

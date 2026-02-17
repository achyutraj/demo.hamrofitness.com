<?php

namespace App\Http\Resources\Swagger\Membership;

use App\Models\GymPurchase;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="SubscriptionResource",
 *     description="Subscription resource",
 *     @OA\Xml(
 *         name="SubscriptionResource"
 *     )
 * )
 */

class SubscriptionResource extends JsonResource
{
      /**
     * @OA\Property(
     *     title="Data",
     *     description="Data wrapper"
     * )
     *
     * @var GymPurchase
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
            'membership_id' => $this->membership_id,
            'purchase_amount' => $this->purchase_amount,
            'paid_amount' => $this->paid_amount,
            'discount' => $this->discount,
            'purchase_date' => $this->purchase_date,
            'start_date' => $this->start_date,
            'next_payment_date' => $this->next_payment_date,
            'expires_on' => $this->expires_on,
            'remarks' => $this->remarks,
            'amount_to_be_paid' => $this->amount_to_be_paid,
            'remaining_amount' => $this->amount_to_be_paid - $this->paid_amount,
            'payment_required' => $this->payment_required,
            'status' => $this->status,
            'deleted_at' => $this->deleted_at,
            'is_renew' => $this->is_renew,
            'membership' => new MembershipPlanResource($this->membership),
        ];
    }
}

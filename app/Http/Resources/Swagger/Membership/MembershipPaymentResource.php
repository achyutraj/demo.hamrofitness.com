<?php

namespace App\Http\Resources\Swagger\Membership;

use App\Models\GymMembershipPayment;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="MembershipPaymentResource",
 *     description="MembershipPayment resource",
 *     @OA\Xml(
 *         name="MembershipPaymentResource"
 *     )
 * )
 */
class MembershipPaymentResource extends JsonResource
{
     /**
     * @OA\Property(
     *     title="Data",
     *     description="Data wrapper"
     * )
     *
     * @var GymMembershipPayment
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
            'purchase' => new SubscriptionResource($this->purchase),
        ];
    }
}

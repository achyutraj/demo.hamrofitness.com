<?php

namespace App\Http\Resources\Swagger\Membership;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="MembershipPlanResource",
 *     description="MembershipPlan resource",
 *     @OA\Xml(
 *         name="MembershipPlanResource"
 *     )
 * )
 */
class MembershipPlanResource extends JsonResource
{
     /**
     * @OA\Property(
     *     title="Data",
     *     description="Data wrapper"
     * )
     *
     * @var \App\Models\GymMembership
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
            'title' => $this->title,
            'price' => $this->price,
            'duration' => $this->duration,
            'duration_type' => $this->duration_type,
            'details' => $this->details,
        ];
    }
}

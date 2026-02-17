<?php

namespace App\Http\Resources\Swagger\GymPlan;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="DietPlanResource",
 *     description="DietPlan resource",
 *     @OA\Xml(
 *         name="DietPlanResource"
 *     )
 * )
 */
class DietPlanResource extends JsonResource
{
      /**
     * @OA\Property(
     *     title="Data",
     *     description="Data wrapper"
     * )
     *
     * @var \App\Models\DietPlan
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
            'days' => $this->days,
            'breakfast' => $this->breakfast,
            'lunch' => $this->lunch,
            'dinner' => $this->dinner,
            'meal_4' => $this->meal_4,
            'meal_5' => $this->meal_5,
        ];
    }
}

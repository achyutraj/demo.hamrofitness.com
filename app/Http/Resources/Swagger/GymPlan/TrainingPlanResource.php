<?php

namespace App\Http\Resources\Swagger\GymPlan;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="TrainingPlanResource",
 *     description="TrainingPlan resource",
 *     @OA\Xml(
 *         name="TrainingPlanResource"
 *     )
 * )
 */
class TrainingPlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $jsonFields = ['days', 'activity', 'sets', 'repetition', 'weights', 'restTime'];
        $this->resource = decodeJsonFields($this->resource, $jsonFields);

        return [
            'id' => $this->id,
            'level' => $this->level,
            'days' => $this->days,
            'activity' => $this->activity,
            'sets' => $this->sets,
            'repetition' => $this->repetition,
            'weights' => $this->weights,
            'restTime' => $this->restTime,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ];
    }
}

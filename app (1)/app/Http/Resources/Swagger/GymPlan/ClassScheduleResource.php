<?php

namespace App\Http\Resources\Swagger\GymPlan;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="ClassScheduleResource",
 *     description="ClassSchedule resource",
 *     @OA\Xml(
 *         name="ClassScheduleResource"
 *     )
 * )
 */
class ClassScheduleResource extends JsonResource
{
    /**
     * @OA\Property(
     *     title="Data",
     *     description="Data wrapper"
     * )
     *
     * @var \App\Models\ClassSchedule
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
        // Define the JSON fields to decode
        $jsonFields = ['days'];

        // Decode JSON fields
        $this->resource = decodeJsonFields($this->resource, $jsonFields);
        $className = $this->classes->class_name ?? null;
        $trainerName = $this->trainers->name ?? null;


        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'className' => $className,
            'trainer' => $trainerName,
            'days' => $this->days,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
        ];
    }
}

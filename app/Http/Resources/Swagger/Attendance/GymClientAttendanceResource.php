<?php

namespace App\Http\Resources\Swagger\Attendance;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="GymClientAttendanceResource",
 *     description="GymClientAttendance resource",
 *     @OA\Xml(
 *         name="GymClientAttendanceResource"
 *     )
 * )
 */
class GymClientAttendanceResource extends JsonResource
{
     /**
     * @OA\Property(
     *     title="Data",
     *     description="Data wrapper"
     * )
     *
     * @var \App\Models\GymClientAttendance
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
            'check_in' => $this->check_in,
            'check_out' => $this->check_out,
            'status' => $this->status,
        ];
    }
}

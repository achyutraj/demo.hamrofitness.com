<?php

namespace App\Http\Resources\Swagger\Locker;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="LockerResource",
 *     description="Locker resource",
 *     @OA\Xml(
 *         name="LockerResource"
 *     )
 * )
 */
class LockerResource extends JsonResource
{
      /**
     * @OA\Property(
     *     title="Data",
     *     description="Data wrapper"
     * )
     *
     * @var \App\Models\Locker
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
            'locker_num' => $this->locker_num,
            'status' => $this->status,
            'details' => $this->details,
            'locker_category' => new LockerCategoryResource($this->lockerCategory),
        ];
    }
}

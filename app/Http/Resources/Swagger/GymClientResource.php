<?php

namespace App\Http\Resources\Swagger;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="GymClientResource",
 *     description="GymClient resource",
 *     @OA\Xml(
 *         name="GymClientResource"
 *     )
 * )
 */
class GymClientResource extends JsonResource
{
     /**
     * @OA\Property(
     *     title="Data",
     *     description="Data wrapper"
     * )
     *
     * @var \App\Models\GymClient
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
        return parent::toArray($request);
    }
}

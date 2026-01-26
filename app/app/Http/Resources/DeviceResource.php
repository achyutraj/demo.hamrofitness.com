<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DeviceResource extends JsonResource
{
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
            'name' => $this->name,
            'port_num' => $this->port_num,
            'code' => $this->code,
            'ip_address' => $this->ip_address,
            'serial_num' => $this->serial_num,
            'device_status' => $this->device_status,
            'device_model' => $this->device_model,
            'device_type' => $this->device_type,
            'vendor_name' => $this->vendor_name,
            'departments' => DepartmentResource::collection($this->departments)
        ];
    }
}

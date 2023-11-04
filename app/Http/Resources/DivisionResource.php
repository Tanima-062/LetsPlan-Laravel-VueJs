<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DivisionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'uniq_id' => $this->uniq_id,
            'name' => $this->name,
            'registration_start_date' => $this->registration_start_date,
            'registration_end_date' => $this->registration_end_date,
            'playgroup_start_date' => $this->playgroup_start_date,
            'playgroup_end_date' => $this->playgroup_end_date,
            'date_parent' => $this->date_parent,
            'open_door_date' => $this->open_door_date,
            'open_door_start_time' => $this->open_door_start_time,
            'open_door_end_time' => $this->open_door_end_time,
            'place' => $this->place,
            'company_id' => $this->company_id,
            'status_id' => $this->status_id,
            'status_name' => $this->status_name,
            'created_at' => $this->created_at,
            'prefix_id' => $this->prefix_id
        ];
    }
}

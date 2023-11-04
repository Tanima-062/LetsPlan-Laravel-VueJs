<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChildrenRegistrationOpeningHoursResource extends JsonResource
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
            'children_registration_id' => $this->children_registration_id,
            'day_id' => $this->day_id,
            'hour_id' => $this->hour_id,
            'color' => $this->color,
            'created_from' => $this->created_from
        ];
    }
}

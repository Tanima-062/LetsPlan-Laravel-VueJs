<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class SpielgruppenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'image' => $this->image ? URL::route('image', ['path' => $this->image, 'w' => 300, 'h' => 300, 'fit' => 'crop']) : null,
            'name' => $this->name,
            'street_name' => $this->street_name,
            'place_id' => $this->place_id,
            'street_number' => $this->street_number,
            'postal_code' => $this->postal_code,
            'location' => $this->location,
            'minimum_occupancy' => $this->minimum_occupancy,
            'maximum_occupancy' => $this->maximum_occupancy,
            'status_id' => $this->status_id,
            'parent_admin_id' => $this->parent_admin_id,
            'company_id' => $this->company_id,
            'status_name' => $this->status->name ?? null,
            'opening_hours' => OpeningHoursResource::collection($this->openingHours),
            'id' => $this->id,
            'file_url' => $this->file_url,
            'morning_time_range' => $this->morning_time_range,
            'afternoon_time_range' => $this->afternoon_time_range
        ];
    }
}

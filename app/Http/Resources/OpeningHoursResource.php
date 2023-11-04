<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OpeningHoursResource extends JsonResource
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
             'spielgruppen_id' => $this->spielgruppen_id,
             'day_id' => $this->day_id,
             'hour_id' => $this->hour_id,
             'lead_id' => $this->lead_id,
             'assistant_id'=> $this->assistant_id,
             'lead' => new UserResource($this->lead),
             'assistant' => new UserResource($this->assistant),
        ];
    }
}

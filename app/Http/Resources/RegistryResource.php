<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RegistryResource extends JsonResource
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
            'id'=>$this->id,
            'parent_admid_id'=>$this->parent_admid_id,
            'company_id'=>$this->company_id,
            'registration_phase_start_date'=>$this->registration_phase_start_date->format('Y-m-d'),
            'registration_phase_end_date'=>$this->registration_phase_end_date->format('Y-m-d'),
            'playground_period_start_date'=>$this->playground_period_start_date->format('Y-m-d'),
            'playground_period_end_date'=>$this->playground_period_end_date->format('Y-m-d'),
            'parents_inform_date'=>$this->parents_inform_date->format('Y-m-d'),
            'open_day_date'=>$this->open_day_date->format('Y-m-d'),
            'open_day_time_from'=>$this->open_day_time_from,
            'open_day_time_to'=>$this->open_day_time_to,
            'content'=>$this->content
        ];
    }
}

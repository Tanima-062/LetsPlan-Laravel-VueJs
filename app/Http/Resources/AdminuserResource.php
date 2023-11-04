<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'company_id' => $this->company_id,
            'name' => $this->name,
            'email' => $this->email,
            'status_id' => $this->status_id,
            'created_at' => $this->created_at,
            'country_code'=> $this->country_code,
            'telephone'=> $this->telephone
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Models\ChildrenRegistrationOpeningHour;
use Illuminate\Http\Resources\Json\JsonResource;

class ChildrenResource extends JsonResource
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
            'child_first_name' => $this->child_first_name,
            'child_last_name' => $this->child_last_name,
            'gender_id' => $this->gender_id,
            'child_birthday' => $this->child_birthday,
            'nationality_id' => $this->nationality_id,
            'nationality_name' => $this->nationality->name??'',
            'mother_tongue_id' => $this->mother_tongue_id ?? null,
            'mother_tongue_name' => $this->motherTongue->name ?? null,
            'other_mother_tongue' => $this->other_mother_tongue ?? null,
            'mother_first_name' => $this->mother_first_name,
            'mother_last_name' => $this->mother_last_name,
            'father_first_name' => $this->father_first_name,
            'father_last_name' => $this->father_last_name,
            'street' => $this->street,
            'street_number' => $this->street_number,
            'postal_code' => $this->postal_code,
            'place' => $this->place,
            'email' => $this->email,
            'phone_number_prefix' => $this->phone_number_prefix,
            'phone_number' => $this->phone_number,
            'mobile_phone_number_prefix' => $this->mobile_phone_number_prefix,
            'mobile_phone_number' => $this->mobile_phone_number,
            'spielgruppen_name' => $this->spielgruppen->unique('id')->pluck('name')->toArray() ?? null,
            'spielgruppens' => $this->spielgruppen,
            'spielgruppen' => $this->selectedSpielgruppen->name ?? 'Keine',
            'spielgruppen_id' => $this->selectedSpielgruppen->id ?? null,
            'closest_spielgruppen' => $this->closestSpielgruppen->name ?? null,
            'closest_spielgruppen_id' => $this->closestSpielgruppen->id ?? null,
            'semester_id' => $this->semester_id,
            'semester_name' => $this->semester->name ?? null,
            'membership_id' => $this->membership_id,
            'note' => $this->note,
            'place_id' => $this->place_id,
            'company_id' => $this->company_id,
            'mail_sent_at' => $this->mail_sent_at,
            'created_from' => $this->created_from,
            'created_at' => $this->created_at,
            'id' => $this->id,
            'openingHours' => $this->slots,
            'status_id' => $this->status_id,
            'marked' => $this->marked,
            'status_name'    =>  $this->status->name,
            'internal_comments' =>  $this->internal_comments,
            'prefix_id' => $this->prefix_id,
            'selected_spielgruppen'  =>  $this->selectedSpielgruppen
        ];
    }
}

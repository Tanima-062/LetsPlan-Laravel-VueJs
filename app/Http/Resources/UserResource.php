<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class UserResource extends JsonResource
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
            'status_id' => $this->status_id,
            'uniq_id' => $this->uniq_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'image' => $this->image ? URL::route('image', ['path' => $this->image, 'w' => 300, 'h' => 300, 'fit' => 'crop']) : null,
            'image_url' => $this->image ? URL::route('image', ['path' => $this->image, 'w' => 300, 'h' => 300, 'fit' => 'crop']) : null,
            'email' => $this->email,
            'email_notifications' => $this->email_notifications,
            'gender_id' => $this->gender_id,
            'role_id' => $this->role_id,
            'role_name' => $this->role->name,
            'role_name_de' => User::getUserByRole($this->role_id),
            'company_id' => $this->company_id,
            'company_name' => ($this->company) ? $this->company->name : null,
            'prefix_id'     =>  $this->prefix_id,
            'created_at' => $this->created_at,
        ];
    }
}

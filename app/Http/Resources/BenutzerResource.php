<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class BenutzerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'image' => $this->image ? URL::route('image', ['path' => $this->image, 'w' => 500, 'h' => 500, 'fit' => 'crop']) : null,
            'role_id' => $this->role_id,
            'role_name' => User::getUserByRole($this->role_id),
            'status_id' => $this->status_id,
            'status_name' => $this->status->name ?? null,
            'street_name' => $this->street_name,
            'house_number' => $this->house_number,
            'postal_code' => $this->postal_code,
            'location' => $this->location,
            'email' => $this->email,
            'prefix' => $this->prefix,
            'phone_number' => $this->phone_number,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'id' => $this->id,
            'created_at' =>  $this->created_at,
            'prefix_id' => $this->prefix_id,
        ];
    }
}

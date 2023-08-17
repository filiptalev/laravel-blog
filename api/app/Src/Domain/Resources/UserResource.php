<?php

namespace App\Src\Domain\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name(),
            'email' => $this->email,
            'role' => $this->role,
            'role_capitalized' => ucfirst($this->role),
            'is_active' => (int)$this->is_active,
        ];

        return $data;
    }
}

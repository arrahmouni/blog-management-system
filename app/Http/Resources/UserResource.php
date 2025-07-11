<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'role'          => $this->role,
            'first_name'    => $this->first_name,
            'last_name'     => $this->last_name,
            'full_name'     => $this->full_name,
            'phone'         => $this->phone,
            'email'         => $this->email,
            'posts_count'   => $this->whenCounted('posts'),
            'created_at'    => $this->created_at,
        ];
    }
}

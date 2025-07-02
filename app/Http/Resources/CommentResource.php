<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'comment'           => $this->body,
            'is_accepted'       => (bool) $this->is_accepted,
            'created_at'        => $this->created_at_format,
            'post'              => new PostResource($this->post),
            'user'              => new UserResource($this->user),
        ];
    }
}

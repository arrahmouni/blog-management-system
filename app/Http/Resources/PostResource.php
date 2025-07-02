<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'title'             => $this->title,
            'slug'              => $this->slug,
            'body'              => $this->body,
            'image_url'         => $this->image_url,
            'is_published'      => (bool) $this->is_published,
            'published_at'      => $this->published_at_format,
            'created_at'        => $this->created_at_format,
            'categories'        => $this->whenLoaded('categories', function () {
                return CategoryResource::collection($this->categories);
            })
        ];
    }
}

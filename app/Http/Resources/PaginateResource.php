<?php

namespace app\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaginateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'total'             => $this->total(),
            'per_page'          => $this->perPage(),
            'current_page'      => $this->currentPage(),
            'last_page'         => $this->lastPage(),
            'from'              => $this->firstItem(),
            'to'                => $this->lastItem(),
            'has_more_pages'    => $this->hasMorePages(),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TokenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'access_token'  => $this->plainTextToken,
            'expires_at'    => Carbon::parse($this->accessToken?->expires_at)->toDateTimeString(),
        ];
    }
}

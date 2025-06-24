<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AspectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'latest_version' => new AspectVersionResource($this->whenLoaded('latestVersion')),
            'versions' => AspectVersionResource::collection($this->whenLoaded('versions')),
        ];
    }
}

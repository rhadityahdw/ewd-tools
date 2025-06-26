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
            'name' => $this->latestVersion->name,
            'description' => $this->latestVersion->description,
            'version_number' => $this->latestVersion->version_number,
            'effective_date' => $this->latestVersion->effective_from->format('Y-m-d H:i:s'),
            'questions_count' => $this->latestVersion->questionVersions->count(),
            'latest_version' => new AspectVersionResource($this->latestVersion),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GuildResource extends JsonResource
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
            'name'          => $this->name,
            'description'   => $this->description,
            'owner'         => $this->user->name,
            'createdAt'     => $this->created_at->format('Y-m-d H:i:s'),
            'channels'      => ChannelResource::collection($this->whenLoaded('channels')),
        ];
    }
}

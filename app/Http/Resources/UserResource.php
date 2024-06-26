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
            'id'                => $this->id,
            'name'              => $this->name,
            'nickname'          => $this->nickname,
            'email'             => $this->email,
            'numberOfGuilds'    => $this->guilds ? count($this->guilds) : 0,
            'numberOfMessages'  => $this->messages ? count($this->messages) : 0,
            'createdAt'         => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'content'       => $this->content,
            'created_at'    => $this->created_at->format('H:i'),
            'user_id'       => $this->user ? $this->user->id : null,
            'user'          => $this->user ? $this->user->nickname : null,
        ];
    }
}

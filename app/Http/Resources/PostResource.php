<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'user' => $this->user,
            'slug'  => $this->slug,
            'comments'  => $this->comments,
            'totalComments' => $this->comments_count,
            'updated_at' => $this->updated_at->format('M j, Y'),
            'deleted_at' => $this->deleted_at,
        ];
    }
}

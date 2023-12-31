<?php

namespace App\Src\Domain\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'body' => $this->body,
            'image' => is_null($this->image) ? null : config('app.url') . $this->image,
            'user' => $this->user,
        ];
    }
}

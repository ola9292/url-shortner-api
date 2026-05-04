<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UrlResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'original_url' => $this->original_url,
            'hash' => $this->hash,
            'short_url' => url('/').'/'.$this->hash,
            'created_at' => $this->created_at,
            // 'clicks' => UrlClickResource::collection($this->whenLoaded('clicks')),
        ];
    }
}

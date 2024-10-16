<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'mag_id' => $this->mag_id,
            'title' => $this->title,
            'subTitle' => $this->sub_title,
            'content' => $this->content,
            'picture' => $this->picture,
            'enable' => $this->enable,
            'created_at' => $this->created_at,
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Helpers\Constant;
use Illuminate\Http\Resources\Json\JsonResource;

class Post extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'status' => $this->status,
            'category_id' => $this->category_id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at->format(Constant::FORMAT_DATE_TIME_API),
            'updated_at' => $this->updated_at->format(Constant::FORMAT_DATE_TIME_API),
        ];
    }
}
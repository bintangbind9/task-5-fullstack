<?php

namespace App\Http\Resources;

use App\Helpers\Constant;
use Illuminate\Http\Resources\Json\JsonResource;

class Category extends JsonResource
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
            'name' => $this->name,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at->format(Constant::FORMAT_DATE_TIME_API),
            'updated_at' => $this->updated_at->format(Constant::FORMAT_DATE_TIME_API),
        ];
    }
}
<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class AdminCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'username'=>$this->username,
            'level'=>$this->level,
            'number'=>$this->number,
            'created_at'=>$this->created_at,
            'created_at'=>$this->updated_at,
        ];
    }
}

<?php

namespace meysammaghsoudi\Todopackage\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use meysammaghsoudi\Todopackage\Http\Resources\LabelAPIResource;

class TaskAPIResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'labels' => LabelAPIResource::collection($this->labels),
            'total' => $this->labels->where('name', $this->name)->count()
        ];
    }
}

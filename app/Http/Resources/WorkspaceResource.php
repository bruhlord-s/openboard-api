<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkspaceResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'group' => $this->whenLoaded('group', function () {
                return GroupResource::make($this->group);
            }),
            'boards' => $this->whenLoaded('boards', function () {
                return BoardResource::collection($this->boards);
            })
        ];
    }
}

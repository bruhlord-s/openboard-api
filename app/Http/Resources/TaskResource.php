<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'description' => $this->description,
            'board_id' => $this->board_id,
            'user_id' => $this->user_id,
            'user' => $this->whenLoaded('user', function () {
                return UserResource::make($this->user);
            }),
            'time_spent' => $this->time_spent,
            'time_estimated' => $this->time_estimated,
        ];
    }
}

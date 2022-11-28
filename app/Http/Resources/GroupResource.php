<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
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
            'users' => $this->whenLoaded('users', function () {
                return UserResource::collection($this->users);
            }),
            'workspaces' => $this->whenLoaded('workspaces', function () {
                return WorkspaceResource::collection($this->workspaces);
            }),
        ];
    }
}

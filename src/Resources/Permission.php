<?php

namespace Moell\Mojito\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class Permission extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'pg_id' => $this->pg_id,
            'name' => $this->name,
            'guard_name' => $this->guard_name,
            'display_name' => $this->display_name,
            'group' => new PermissionGroup($this->group),
            'icon' => $this->icon,
            'sequence' => $this->sequence,
            'description' => $this->description,
            'created_name' => $this->created_name,
            'updated_name' => $this->updated_name,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->created_at
        ];
    }
}
<?php

namespace Moell\Mojito\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminUser extends JsonResource
{
    public function toArray($request)
    {
        if ($this->resourceAttrs) {
            return array_only($this->resource->toArray(), $this->resourceAttrs);
        } else {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'username' => $this->username,
                'status' => $this->status ? true : false,
                'created_at' => (string) $this->created_at,
                'updated_at' => (string) $this->updated_at,
            ];
        }
    }
}

<?php

namespace Moell\Mojito\Resources;

use Illuminate\Http\Resources\Json\Resource;

class AdminUser extends Resource {
	public function toArray($request) {
		return [
			'id' => $this->id,
			'name' => $this->name,
			'email' => $this->email,
			'avatar' => $this->avatar,
			'mobile' => $this->mobile,
			'roles' => $this->roles->pluck('name'),
			'farm' => optional($this->farm)->pluck('id'),
			'shop' => optional($this->shop)->pluck('id'),
			'created_at' => (string) $this->created_at,
			'updated_at' => (string) $this->updated_at,
		];
	}
}
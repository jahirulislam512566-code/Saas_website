<?php

namespace App\Http\Resources;

use App\Http\Resources\RoleResource;

class UserResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company,
            'job_title' => $this->job_title,
            'department' => $this->department,
            'timezone' => $this->timezone,
            'language' => $this->language,
            'is_active' => $this->is_active,
            'is_verified' => $this->hasVerifiedEmail(),
            'avatar' => $this->avatar ? asset($this->avatar) : null,
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'permissions' => $this->when($this->relationLoaded('permissions'), function () {
                return $this->permissions->pluck('name');
            }),
            'created_at' => $this->formatDate($this->created_at, 'M d, Y'),
            'updated_at' => $this->formatDate($this->updated_at, 'M d, Y'),
            'last_login_at' => $this->formatDate($this->last_login_at, 'M d, Y H:i'),
        ];
    }

    public function with($request): array
    {
        return array_merge(parent::with($request), [
            'resource' => 'user',
        ]);
    }
}
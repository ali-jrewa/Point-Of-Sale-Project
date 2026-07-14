<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['name', 'display_name'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Check if the authenticated user's role has a given permission.
     */
    public function hasPermission(string $permissionName): bool
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->permissions->contains('name', $permissionName);
    }

    public function hasAnyPermission(array $permissionNames): bool
    {
        return collect($permissionNames)->contains(fn ($name) => $this->hasPermission($name));
    }
}

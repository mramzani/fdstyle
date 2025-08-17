<?php

namespace App\Services\Permission\Traits;


use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;
use Modules\Role\Entities\Permission;

trait HasPermission
{
    /**
     * @return BelongsToMany
     */
    public function permissions():BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * If you do not want to detach existing IDs
        * that are missing from the given array,
        * you may use the givePermissionTo method
     * @param array ...$permissions
     * @return $this
     */
    public function givePermissionTo(...$permissions)
    {
        $permissions = $this->getAllPermission($permissions);

        if ($permissions->isEmpty()) return $this;

        $this->permissions()->syncWithoutDetaching($permissions);

        return $this;
    }

    /**
     * Detach all Permission from the user
     * @param mixed ...$permissions
     * @return $this
     */
    public function withdrawPermissions(...$permissions): HasPermission
    {
        $permissions = $this->getAllPermission($permissions);
        $this->permissions()->detach($permissions);
        return $this;
    }

    /**
     * The refreshPermissions method accepts an array of IDs to place on the intermediate table.
     * Any IDs that are not in the given array will be removed from the intermediate table.
     * So, after this operation is complete, only the IDs in the given array will
     * exist in the intermediate table
     * @param mixed ...$permissions
     * @return $this
     */
    public function refreshPermissions(...$permissions): HasPermission
    {
        $permissions = $this->getAllPermission($permissions);
        $this->permissions()->sync($permissions);
        return $this;
    }


    /**
     * @param Permission $permission
     * @return bool
     */
    public function hasPermissions(Permission $permission): bool
    {
        return $this->hasPermissionThroughRole($permission) || $this->permissions->contains($permission);
    }

    /**
     * @param Permission $permission
     * @return bool
     */
    protected function hasPermissionThroughRole(Permission $permission): bool
    {
        foreach ($permission->roles as $role) {
            if ($this->roles->contains($role)) return true;
        }
        return false;
    }

    /**
     * @param array $permissions
     * @return mixed
     */
    public function getAllPermission(array $permissions)
    {
        return Permission::whereIn('name', Arr::flatten($permissions))->get();
    }
}

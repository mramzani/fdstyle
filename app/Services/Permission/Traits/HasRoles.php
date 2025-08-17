<?php

namespace App\Services\Permission\Traits;



use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;
use Modules\Role\Entities\Role;

trait HasRoles
{
    public function roles():BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function giveRolesTo(...$roles)
    {
        $roles = $this->getAllRoles($roles);

        if ($roles->isEmpty()) return $this;

        $this->roles()->syncWithoutDetaching($roles);

        return $this;
    }

    public function withdrawRoles(...$roles)
    {
        $roles = $this->getAllRoles($roles);

        $this->roles()->detach($roles);

        return $this;
    }

    public function refreshRoles(...$roles)
    {
        $roles = $this->getAllRoles($roles);

        $this->roles()->sync($roles);

        return $this;
    }

    /**
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return $this->roles->contains('name',$role);
    }

    public function getAllRoles(array $roles)
    {
        return Role::whereIn('name', Arr::flatten($roles))->get();
    }

    /**
     * @param array $roles
     * @return array
     */
    public function getRolePermission(array $roles): array
    {
        $permissions = [];
        foreach ($this->getAllRoles($roles) as $role) {
            foreach ($role->permissions as $permission){
                $permissions[] = $permission->name;
            }
        }
        return $permissions;
    }
}

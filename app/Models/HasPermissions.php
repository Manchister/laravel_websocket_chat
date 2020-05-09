<?php

namespace App\Models;


trait HasPermissions
{


    public function is_block_from($roomId, $roleId)
    {
        return  collect($this->block_user)
            ->where('room_id', '=', $roomId)
            ->where('role_id', '=', $roleId)
            ->count() > 0;

//        $room = $this->block_user
//            ->pluck('room_id')->contains($roomId);
//        if ($room && $role)return true;
//
//        return false;
        /*where([
            ['role_id', '=', $roleId],
            ['room_id', '=', $roomId],
        ])->count();*/
    }
    /**
     * Check if user has permission.
     *
     * @param $ability
     * @param array $arguments
     *
     * @return bool
     */
    public function can($ability, $arguments = []): bool
    {
        if (empty($ability)) {
            return true;
        }

        if ($this->isAdministrator()) {
            return true;
        }

        /*if ($this->user_roles->pluck('slug')->contains($ability)) {
            return true;
        }*/

        return $this->user_roles->pluck('slug')->contains($ability);
    }

    /**
     * Check if user has no permission.
     *
     * @param $user_roles
     *
     * @return bool
     */
    /*public function cannot(string $user_roles): bool
    {
        return !$this->can($user_roles);
    }*/

    /**
     * Check if user is administrator.
     *
     * @return mixed
     */
    public function isAdministrator(): bool
    {
        return $this->isRole('administrator');
    }

    /**
     * Check if user is $role.
     *
     * @param string $user_role
     *
     * @return mixed
     */
    public function isRole(string $user_role): bool
    {
        return $this->user_roles->pluck('slug')->contains($user_role);
    }

    /**
     * Check if user in $roles.
     *
     * @param array $user_roles
     *
     * @return mixed
     */
    public function inRoles(array $user_roles = []): bool
    {
        return $this->user_roles->pluck('slug')->intersect($user_roles)->isNotEmpty();
    }

    /**
     * If visible for roles.
     *
     * @param $user_roles
     *
     * @return bool
     */
    public function visible(array $user_roles = []): bool
    {
        if (empty($user_roles)) {
            return true;
        }

        $user_roles = array_column($user_roles, 'slug');

        return $this->inRoles($user_roles) || $this->isAdministrator();
    }

    /**
     * Detach models from the relationship.
     *
     * @return void
     */
    protected static function bootHasPermissions()
    {
        static::deleting(function ($model) {
            $model->user_roles()->detach();
        });
    }
}

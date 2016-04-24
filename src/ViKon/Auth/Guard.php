<?php

namespace ViKon\Auth;

use Illuminate\Auth\SessionGuard;
use Illuminate\Database\Eloquent\Collection;
use ViKon\Auth\Contracts\Keeper;
use ViKon\Auth\Model\User;

/**
 * Class Guard
 *
 * @author  Kovács Vince <vincekovacs@hotmail.com>
 *
 * @package ViKon\Auth
 *
 * @method \ViKon\Auth\Model\User getLastAttempted()
 */
class Guard extends SessionGuard implements Keeper
{
    /** @type string[]|null */
    protected $groups;

    /** @type string[]|null */
    protected $roles;

    /** @type string[]|null */
    protected $permissions;

    /**
     * {@inheritDoc}
     */
    public function attempt(array $credentials = [], $remember = false, $login = true)
    {
        if (!array_key_exists('namespace', $credentials)) {
            $credentials['namespace'] = '';
        }

        return parent::attempt($credentials, $remember, $login);
    }

    /**
     * Check if authenticate user has group
     *
     * @param string $group
     *
     * @return bool|null return NULL if user is not authenticated
     */
    public function hasGroup($group)
    {
        if ($this->user() === null) {
            return null;
        }

        return in_array($group, $this->groups, true);
    }

    /**
     * Check if authenticated user has all groups passed by parameter
     *
     * @param string[] $groups
     *
     * @return bool|null return NULL if user is not authenticated
     */
    public function hasGroups($groups)
    {
        if ($this->user() === null) {
            return null;
        }

        if (!is_array($groups)) {
            $groups = func_get_args();
        }

        // Count roles because user need user to have all roles passed as parameter
        return count(array_intersect($groups, $this->groups)) === count($groups);
    }

    /**
     * Check if authenticate user has role
     *
     * @param string $role
     *
     * @return bool|null return NULL if user is not authenticated
     */
    public function hasRole($role)
    {
        if ($this->user() === null) {
            return null;
        }

        return in_array($role, $this->roles, true);
    }

    /**
     * Check if authenticated user has all roles passed by parameter
     *
     * @param string[] $roles
     *
     * @return bool|null return NULL if user is not authenticated
     */
    public function hasRoles($roles)
    {
        if ($this->user() === null) {
            return null;
        }

        if (!is_array($roles)) {
            $roles = func_get_args();
        }

        // Count roles because user need user to have all roles passed as parameter
        return count(array_intersect($roles, $this->roles)) === count($roles);
    }

    /**
     * Check if authenticated user has permission
     *
     * @param string $permission
     *
     * @return bool|null return NULL if user is not authenticated
     */
    public function hasPermission($permission)
    {
        if ($this->user() === null) {
            return null;
        }

        return in_array($permission, $this->permissions, true);
    }

    /**
     * Check if authenticated user has all permission passed by parameter
     *
     * @param string[] $permissions
     *
     * @return bool|null return NULL if user is not authenticated
     */
    public function hasPermissions($permissions)
    {
        if ($this->user() === null) {
            return null;
        }

        if (!is_array($permissions)) {
            $permissions = func_get_args();
        }

        // Count permissions because user need user to have all permissions passed as parameter
        return count(array_intersect($permissions, $this->permissions)) === count($permissions);
    }

    /**
     * {@inheritDoc}
     *
     * @return \ViKon\Auth\Model\User|null
     */
    public function user()
    {
        $user = parent::user();

        // Load roles if user is set first time
        if ($user !== null && ($this->roles === null || $this->permissions === null)) {
            $this->groups      = [];
            $this->roles       = [];
            $this->permissions = [];

            // Load roles only if User model is permission based user
            if ($user instanceof User) {
                $this->addGroups($user->groups);
                $this->addRoles($user->roles);
                $this->addPermissions($user->permissions);

                $this->roles       = array_unique($this->roles);
                $this->permissions = array_unique($this->permissions);
            }
        }

        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function logout()
    {
        parent::logout();

        $this->groups      = null;
        $this->roles       = null;
        $this->permissions = null;
    }

    /**
     * Return if current user is blocked
     *
     * If user is not role based user, then always return false
     *
     * @return bool|null return NULL if user is not authenticated
     */
    public function isBlocked()
    {
        if ($this->user() === null) {
            return null;
        }

        // Check user blocking status only if User model is role based user
        if ($this->user instanceof User) {
            return $this->user->blocked;
        }

        return false;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\Group[] $groups
     *
     * @return void
     */
    protected function addGroups(Collection $groups)
    {
        foreach ($groups as $group) {
            $this->groups[] = $group->token;
            $this->addRoles($group->roles);
            $this->addPermissions($group->permissions);
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\Role[] $roles
     *
     * @return void
     */
    protected function addRoles(Collection $roles)
    {
        foreach ($roles as $role) {
            $this->roles[] = $role->token;
            $this->addPermissions($role->permissions);
        }
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\Permission[] $permissions
     *
     * @return void
     */
    protected function addPermissions(Collection $permissions)
    {
        foreach ($permissions as $permission) {
            $this->permissions[] = $permission->token;
        }
    }

}
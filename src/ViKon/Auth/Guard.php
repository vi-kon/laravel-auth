<?php

namespace ViKon\Auth;

use ViKon\Auth\Model\User;

/**
 * Class Guard
 *
 * @author  Kovács Vince <vincekovacs@hotmail.com>
 *
 * @package ViKon\Auth
 */
class Guard extends \Illuminate\Auth\Guard
{
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
            $credentials['namespace'] = null;
        }

        return parent::attempt($credentials, $remember, $login);
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
     */
    public function user()
    {
        $user = parent::user();

        // Load roles if user is set first time
        if ($user !== null && ($this->roles === null || $this->permissions === null)) {
            $this->roles       = [];
            $this->permissions = [];

            // Load roles only if User model is role based user
            if ($user instanceof User) {
                // Load permissions from roles
                $roles = $user->roles;
                foreach ($roles as $role) {
                    $this->roles[] = $role->token;
                    foreach ($role->permissions as $permission) {
                        $this->permissions[] = $permission->token;
                    }
                }

                // Load permissions from users
                $permissions = $user->permissions;
                foreach ($permissions as $permission) {
                    $this->permissions[] = $permission->token;
                }

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

}
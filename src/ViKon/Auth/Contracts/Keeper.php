<?php

namespace ViKon\Auth\Contracts;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;

/**
 * Interface Keeper
 *
 * @package Contracts
 *
 * @author  Kovács Vince<vincekovacs@hotmail.com>
 */
interface Keeper extends Guard, StatefulGuard
{
    /**
     * Check if authenticate user has group
     *
     * @param string $group
     *
     * @return bool|null return NULL if user is not authenticated
     */
    public function hasGroup($group);

    /**
     * Check if authenticated user has all groups passed by parameter
     *
     * @param string[] $groups
     *
     * @return bool|null return NULL if user is not authenticated
     */
    public function hasGroups($groups);

    /**
     * Check if authenticate user has role
     *
     * @param string $role
     *
     * @return bool|null return NULL if user is not authenticated
     */
    public function hasRole($role);

    /**
     * Check if authenticated user has all roles passed by parameter
     *
     * @param string[] $roles
     *
     * @return bool|null return NULL if user is not authenticated
     */
    public function hasRoles($roles);

    /**
     * Check if authenticated user has permission
     *
     * @param string $permission
     *
     * @return bool|null return NULL if user is not authenticated
     */
    public function hasPermission($permission);

    /**
     * Check if authenticated user has all permission passed by parameter
     *
     * @param string[] $permissions
     *
     * @return bool|null return NULL if user is not authenticated
     */
    public function hasPermissions($permissions);

    /**
     * Return if current user is blocked
     *
     * If user is not role based user, then always return false
     *
     * @return bool|null return NULL if user is not authenticated
     */
    public function isBlocked();
}
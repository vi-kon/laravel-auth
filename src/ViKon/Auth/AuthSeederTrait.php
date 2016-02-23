<?php

namespace ViKon\Auth;

use ViKon\Auth\Model\Group;
use ViKon\Auth\Model\Permission;
use ViKon\Auth\Model\Role;
use ViKon\Auth\Model\User;

/**
 * Class AuthSeederTrait
 *
 * @author  Kovács Vince <vincekovacs@hotmail.com>
 *
 * @package ViKon\Auth
 */
trait AuthSeederTrait
{
    /**
     * Create new user model instance
     *
     * @param string $username
     * @param string $password
     * @param array  $options
     *
     * @return \ViKon\Auth\Model\User
     */
    protected function newUserModel($username, $password, array $options = [])
    {
        $user           = new User();
        $user->username = $username;
        $user->password = $password;

        foreach ($options as $key => $value) {
            $user->{$key} = $value;
        }

        return $user;
    }

    /**
     * Create and store in database new user model instance
     *
     * @param string $username
     * @param string $password
     * @param array  $options
     *
     * @return \ViKon\Auth\Model\User
     */
    protected function createUserModel($username, $password, array $options = [])
    {
        $user = $this->newUserModel($username, $password, $options);
        $user->save();

        return $user;
    }

    /**
     * Create single user record
     *
     * @param string $username authentication username
     * @param string $password authentication password
     * @param array  $options  additional column values
     *
     * @return array array with user field values
     */
    protected function createUserRecord($username, $password, array $options = [])
    {
        return [
            // Username
            User::FIELD_USERNAME => $username,
            // Password
            User::FIELD_PASSWORD => bcrypt($password),
            // Email
            User::FIELD_EMAIL    => array_key_exists(User::FIELD_EMAIL, $options)
                ? $options[User::FIELD_EMAIL]
                : $username . '@local . com',
            // Home
            User::FIELD_HOME     => array_key_exists(User::FIELD_HOME, $options)
                ? $options[User::FIELD_HOME]
                : null,
            // Is blocked
            User::FIELD_BLOCKED  => array_key_exists(User::FIELD_BLOCKED, $options)
                ? $options[User::FIELD_BLOCKED]
                : false,
            // Is static
            User::FIELD_STATIC   => array_key_exists(User::FIELD_STATIC, $options)
                ? $options[User::FIELD_STATIC]
                : false,
            // Is hidden
            User::FIELD_HIDDEN   => array_key_exists(User::FIELD_HIDDEN, $options)
                ? $options[User::FIELD_HIDDEN]
                : false,
        ];
    }

    /**
     * Create new group model instance
     *
     * @param string $token   group token
     * @param array  $options additional column valuesF
     *
     * @return \ViKon\Auth\Model\Group
     */
    protected function newGroupModel($token, array $options = [])
    {
        $group        = new Group();
        $group->token = $token;

        foreach ($options as $key => $value) {
            $group->{$key} = $value;
        }

        return $group;
    }

    /**
     * Create and store in database new group model instance
     *
     * @param string $token   group token
     * @param array  $options additional column values
     *
     * @return \ViKon\Auth\Model\Group
     */
    protected function createGroupModel($token, array $options = [])
    {
        $group = $this->newGroupModel($token, $options);
        $group->save();

        return $group;
    }

    /**
     * Create new role model instance
     *
     * @param string $token   role token
     * @param array  $options additional column valuesF
     *
     * @return \ViKon\Auth\Model\Role
     */
    protected function newRoleModel($token, array $options = [])
    {
        $role        = new Role();
        $role->token = $token;

        foreach ($options as $key => $value) {
            $role->{$key} = $value;
        }

        return $role;
    }

    /**
     * Create and store in database new role model instance
     *
     * @param string $token   role token
     * @param array  $options additional column values
     *
     * @return \ViKon\Auth\Model\Role
     */
    protected function createRoleModel($token, array $options = [])
    {
        $role = $this->newRoleModel($token, $options);
        $role->save();

        return $role;
    }

    /**
     * Create new permission model instance
     *
     * @param string $token
     * @param array  $options
     *
     * @return \ViKon\Auth\Model\Permission
     */
    protected function newPermissionModel($token, array $options = [])
    {
        $permission        = new Permission();
        $permission->token = $token;

        foreach ($options as $key => $value) {
            $permission->{$key} = $value;
        }

        return $permission;
    }

    /**
     * Create and store in database new permission model instance
     *
     * @param string $token
     * @param array  $options
     *
     * @return \ViKon\Auth\Model\Permission
     */
    protected function createPermissionModel($token, array $options = [])
    {
        $permission = $this->newPermissionModel($token, $options);
        $permission->save();

        return $permission;
    }

}
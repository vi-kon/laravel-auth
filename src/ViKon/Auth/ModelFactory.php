<?php

namespace ViKon\Auth;

use ViKon\Auth\Model\Permission;
use ViKon\Auth\Model\Role;
use ViKon\Auth\Model\User;

class ModelFactory
{
    /**
     * @param string $username
     * @param string $email
     * @param string $password
     * @param array  $attributes
     *
     * @return \ViKon\Auth\Model\User
     */
    public function createUser($username, $email, $password, array $attributes = [])
    {
        $user           = new User();
        $user->username = strtolower(trim($username));
        $user->email    = trim($email);
        $user->password = bcrypt($password);

        foreach ($attributes as $key => $attribute) {
            $user->{$key} = $attribute;
        }

        return $user;
    }

    /**
     * @param string $token
     * @param array  $attributes
     *
     * @return \ViKon\Auth\Model\Role
     */
    public function createRole($token, array $attributes = [])
    {
        $role        = new Role();
        $role->token = trim($token);

        foreach ($attributes as $key => $attribute) {
            $role->{$key} = $attribute;
        }

        return $role;
    }

    /**
     * @param string $token
     *
     * @return \ViKon\Auth\Model\Permission
     */
    public function createPermission($token)
    {
        $permission        = new Permission();
        $permission->token = trim($token);

        return $permission;
    }
}
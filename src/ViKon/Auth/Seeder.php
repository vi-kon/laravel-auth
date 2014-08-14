<?php

namespace ViKon\Auth;

use ViKon\Auth\models\Group;
use ViKon\Auth\models\Role;
use ViKon\Auth\models\User;

/**
 * Trait Seeder
 *
 * @author  Kovács Vince <vincekovacs@hotmail.com>
 *
 * @package ViKon\Auth
 *
 */
trait Seeder
{
    /**
     * @param string      $username User username
     * @param string      $password User password
     * @param string      $email    User e-mail address
     * @param string|null $home     Home route name (After successful login redirect here)
     * @param bool        $static   User is static or not (Disable deleting on GUI)
     * @param bool        $hidden   User is hidden or not (Disable showing on GUI)
     *
     * @return \ViKon\Auth\models\User|static
     */
    protected function createUser($username, $password, $email, $home = null, $static = false, $hidden = false)
    {
        $user = User::create(array(
                                 'username' => $username,
                                 'password' => \Hash::make($password),
                                 'email'    => $email,
                                 'home'     => $home,
                                 'static'   => $static,
                                 'hidden'   => $hidden,
                             ));

        return $user;
    }

    /**
     * @param string $name   Group human readable name
     * @param string $token  Group token
     * @param bool   $static Group is static or not (Disable deleting on GUI)
     * @param bool   $hidden Group is hidden or not (Disable showing on GUI)
     *
     * @return \ViKon\Auth\models\Group|static
     */
    protected function createGroup($name, $token, $static = false, $hidden = false)
    {
        $group = Group::create(array(
                                   'name'   => $name,
                                   'token'  => $token,
                                   'static' => $static,
                                   'hidden' => $hidden,
                               ));

        return $group;
    }

    /**
     * @param $name Role unique name
     *
     * @return \ViKon\Auth\models\Role|static
     */
    protected function createRole($name)
    {
        $role = Role::create(array(
                                 'name' => $name,
                             ));

        return $role;
    }
}
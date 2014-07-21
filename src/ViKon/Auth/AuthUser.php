<?php


namespace ViKon\Auth;

use ViKon\Auth\models\User;

/**
 * Class AuthUser
 *
 * @author  Kovács Vince <vincekovacs@hotmail.com>
 *
 * @package ViKon\Auth
 */
class AuthUser
{

    /**
     * @var null|\ViKon\Auth\models\User
     */
    private $user = null;
    /**
     * @var array
     */
    private $roles = array();

    public function __construct()
    {
        if (\Auth::check())
        {
            $this->user = \Auth::getUser();
            if (!$this->user instanceof User)
            {
                $this->user = null;
                \Log::debug('User is not instance of "ViKon\Auth\models\User"');

                return;
            }
            $roles  = $this->user->roles;
            $groups = $this->user->groups;

            foreach ($roles as $role)
            {
                $this->roles[] = $role->name;
            }

            foreach ($groups as $group)
            {
                $roles = $group->roles();
                foreach ($roles->get() as $role)
                {
                    $this->roles[] = $role->name;
                }
            }
            $this->roles = array_unique($this->roles);
        }
    }

    /**
     * Check if current user has role
     *
     * @param string $role role name
     *
     * @return bool
     */
    public function hasRole($role)
    {
        return in_array($role, $this->roles);
    }

    /**
     * Check if current user has roles
     *
     * @param array|string $roles roles array
     *
     * @return bool
     */
    public function hasRoles($roles)
    {
        if (!is_array($roles))
        {
            $roles = func_get_args();
        }

        $reaming = count($roles);
        if ($reaming == 0)
        {
            return true;
        }

        foreach ($roles as $role)
        {
            if (in_array($role, $this->roles))
            {
                $reaming--;
                if ($reaming == 0)
                {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Return current user instance
     *
     * @return null|\ViKon\Auth\models\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Return if current user is blocked
     *
     * @return bool
     */
    public function isBlocked()
    {
        return $this->user !== null && $this->user->blocked;
    }
}
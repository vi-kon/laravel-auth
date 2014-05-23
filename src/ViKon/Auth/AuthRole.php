<?php


namespace ViKon\Auth;

class AuthRole
{

    private $roles = array();

    public function __construct()
    {
        if (\Auth::check())
        {
            $user   = \Auth::getUser();
            $roles  = $user->roles();
            $groups = $user->groups();

            foreach ($roles->get() as $role)
            {
                $this->roles[] = $role->name;
            }

            foreach ($groups->get() as $group)
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
     * @param string $role
     *
     * @return bool
     */
    public function hasRole($role)
    {
        return in_array($role, $this->roles());
    }

    /**
     * Check if current user has roles
     *
     * @param array|string $roles
     *
     * @return bool
     */
    public function hasRoles($roles)
    {
        if (!is_array($roles))
        {
            $roles = get_func_get_args();
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
}
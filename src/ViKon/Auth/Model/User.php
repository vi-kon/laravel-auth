<?php

namespace ViKon\Auth\Model;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use ViKon\Auth\Exception\ProfileNotFoundException;

/**
 * Class User
 *
 * @package ViKon\Auth\Model
 *
 * @author  Kovács Vince<vincekovacs@hotmail.com>
 *
 * @property integer                                                                                $id
 * @property string                                                                                 $username
 * @property string                                                                                 $password
 * @property string                                                                                 $email
 * @property string                                                                                 $remember_token
 * @property string                                                                                 $home
 * @property string                                                                                 $namespace
 * @property boolean                                                                                $blocked
 * @property boolean                                                                                $static
 * @property boolean                                                                                $hidden
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\Permission[]           $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\Role[]                 $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\Group[]                $groups
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\UserPasswordReminder[] $reminders
 * @property-read \Illuminate\Database\Eloquent\Model                                               $profile
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    const FIELD_ID             = 'id';
    const FIELD_USERNAME       = 'username';
    const FIELD_PASSWORD       = 'password';
    const FIELD_EMAIL          = 'email';
    const FIELD_HOME           = 'home';
    const FIELD_NAMESPACE      = 'namespace';
    const FIELD_BLOCKED        = 'blocked';
    const FIELD_STATIC         = 'static';
    const FIELD_HIDDEN         = 'hidden';
    const FIELD_REMEMBER_TOKEN = 'remember_token';

    /**
     * {@inheritDoc}
     */
    public function __construct(array $attributes = [])
    {
        $this->table      = config('vi-kon.auth.table.users');
        $this->timestamps = false;
        $this->hidden     = [static::FIELD_PASSWORD, static::FIELD_REMEMBER_TOKEN];
        $this->casts      = [
            static::FIELD_USERNAME       => 'string',
            static::FIELD_PASSWORD       => 'string',
            static::FIELD_EMAIL          => 'string',
            static::FIELD_HOME           => 'string',
            static::FIELD_NAMESPACE      => 'string',
            static::FIELD_BLOCKED        => 'boolean',
            static::FIELD_STATIC         => 'boolean',
            static::FIELD_HIDDEN         => 'boolean',
            static::FIELD_REMEMBER_TOKEN => 'string',
        ];
        $this->guarded    = [
            static::FIELD_USERNAME,
            static::FIELD_PASSWORD,
        ];

        parent::__construct($attributes);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, config('vi-kon.auth.table.rel__user__permission'), 'user_id', 'permission_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, config('vi-kon.auth.table.rel__user__role'), 'user_id', 'role_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, config('vi-kon.auth.table.rel__user__group'), 'user_id', 'group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reminders()
    {
        return $this->hasMany(UserPasswordReminder::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|null
     *
     * @throws \ViKon\Auth\Exception\ProfileNotFoundException
     */
    public function profile()
    {
        if (class_exists(config('vi-kon.auth.profile'))) {
            return $this->hasOne(config('vi-kon.auth.profile'), 'user_id', 'id');
        }

        throw new ProfileNotFoundException('Provided profile class not found (' . config('vi-kon.auth.profile') . ')');
    }

    /**
     * Set username to lowercase
     *
     * @param string $username
     *
     * @return void
     */
    public function setUsernameAttribute($username)
    {
        $this->attributes[static::FIELD_USERNAME] = strtolower($username);
    }

    /**
     * Hash password for user model
     *
     * @param string $password
     *
     * @return void
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes[static::FIELD_PASSWORD] = app('hash')->make($password);
    }

    /**
     * Check if user has given group
     *
     * @param string $group
     *
     * @return bool
     */
    public function hasGroup($group)
    {
        return !$this->groups->where('token', $group)->isEmpty();
    }

    /**
     * Check if user has given role
     *
     * @param string $role
     *
     * @return bool
     */
    public function hasRole($role)
    {
        if (!$this->roles->where('token', $role)->isEmpty()) {
            return true;
        }

        foreach ($this->groups as $group) {
            if (!$group->roles->where('token', $role)->isEmpty()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has given permission
     *
     * @param string $permission
     *
     * @return bool
     */
    public function hasPermission($permission)
    {
        if (!$this->permissions->where('token', $permission)->isEmpty()) {
            return true;
        }

        foreach ($this->groups as $group) {
            foreach ($group->roles as $role) {
                if (!$role->permissions->where('token', $permission)->isEmpty()) {
                    return true;
                }
            }
        }

        foreach ($this->roles as $role) {
            if (!$role->permissions->where('token', $permission)->isEmpty()) {
                return true;
            }
        }

        return false;
    }
}

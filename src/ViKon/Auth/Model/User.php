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
 * @property boolean                                                                                $blocked
 * @property boolean                                                                                $static
 * @property boolean                                                                                $hidden
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\Role[]                 $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\Group[]                $groups
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\UserPasswordReminder[] $reminders
 * @property-read \UserProfile                                                                      $profile
 *
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\User whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\User whereHome($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\User whereBlocked($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\User whereStatic($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\User whereHidden($value)
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * {@inheritDoc}
     */
    public function __construct(array $attributes = [])
    {
        $this->table      = 'users';
        $this->timestamps = false;
        $this->hidden     = ['password', 'remember_token'];

        parent::__construct($attributes);
    }

    /** @noinspection ClassMethodNameMatchesFieldNameInspection */
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'rel_user_role', 'user_id', 'role_id');
    }

    /** @noinspection ClassMethodNameMatchesFieldNameInspection */
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'rel_user_group', 'user_id', 'group_id');
    }

    /** @noinspection ClassMethodNameMatchesFieldNameInspection */
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reminders()
    {
        return $this->hasMany(UserPasswordReminder::class, 'user_id', 'id');
    }

    /** @noinspection ClassMethodNameMatchesFieldNameInspection */
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|null
     *
     * @throws \ViKon\Auth\Exception\ProfileNotFoundException
     */
    public function profile()
    {
        if (class_exists(config('auth-role.profile'))) {
            return $this->hasOne(config('auth-role.profile'), 'user_id', 'id');
        }

        throw new ProfileNotFoundException('Provided profile class not found (' . config('auth-role.profile') . ')');
    }
}

<?php

namespace ViKon\Auth\Model;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use ViKon\Auth\Database\Eloquent\Model;
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\Permission[]           $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\Role[]                 $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\UserPasswordReminder[] $reminders
 * @property-read \Illuminate\Database\Eloquent\Model                                               $profile
 *
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\User whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\User whereHome($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\User whereNamespace($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\User whereBlocked($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\User whereStatic($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\User whereHidden($value)
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    const FIELD_ID        = 'id';
    const FIELD_USERNAME  = 'username';
    const FIELD_PASSWORD  = 'password';
    const FIELD_EMAIL     = 'email';
    const FIELD_HOME      = 'home';
    const FIELD_NAMESPACE = 'namespace';
    const FIELD_BLOCKED   = 'blocked';
    const FIELD_STATIC    = 'static';
    const FIELD_HIDDEN    = 'hidden';

    /**
     * {@inheritDoc}
     */
    public function __construct(array $attributes = [])
    {
        $this->table      = static::$config->get('vi-kon.auth.table.users');
        $this->timestamps = false;
        $this->hidden     = ['password', 'remember_token'];

        parent::__construct($attributes);
    }

    /** @noinspection ClassMethodNameMatchesFieldNameInspection */
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, static::$config->get('vi-kon.auth.table.rel__user__permission'), 'user_id', 'permission_id');
    }

    /** @noinspection ClassMethodNameMatchesFieldNameInspection */
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, static::$config->get('vi-kon.auth.table.rel__user__role'), 'user_id', 'role_id');
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
        if (class_exists(static::$config->get('vi-kon.auth.profile'))) {
            return $this->hasOne(static::$config->get('vi-kon.auth.profile'), 'user_id', 'id');
        }

        throw new ProfileNotFoundException('Provided profile class not found (' . static::$config->get('vi-kon.auth.profile') . ')');
    }
}

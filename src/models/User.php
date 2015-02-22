<?php

namespace ViKon\Auth\models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;

/**
 * ViKon\Auth\models\User
 *
 * @property integer                                                                                 $id
 * @property string                                                                                  $username
 * @property string                                                                                  $password
 * @property string                                                                                  $email
 * @property string                                                                                  $remember_token
 * @property string                                                                                  $home
 * @property boolean                                                                                 $blocked
 * @property boolean                                                                                 $static
 * @property boolean                                                                                 $hidden
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\models\Role[]                 $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\models\Group[]                $groups
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\models\UserPasswordReminder[] $reminders
 * @property-read \UserProfile                                                                       $profile
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\models\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\models\User whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\models\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\models\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\models\User whereHome($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\models\User whereBlocked($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\models\User whereStatic($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\models\User whereHidden($value)
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract {
    use Authenticatable, CanResetPassword;

    /**
     *
     * Disable updated_at and created_at columns
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * The database table used by the model (mysql).
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The database table used by the model (mongodb).
     *
     * @var string
     */
    protected $collection = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles() {
        return $this->belongsToMany('ViKon\Auth\models\Role', 'rel_user_role', 'user_id', 'role_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups() {
        return $this->belongsToMany('ViKon\Auth\models\Group', 'rel_user_group', 'user_id', 'group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reminders() {
        return $this->hasMany('ViKon\Auth\models\UserPasswordReminder', 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|null
     */
    public function profile() {
        if (class_exists('App\UserProfile')) {
            return $this->hasOne('App\UserProfile', 'user_id', 'id');
        }

        return null;
    }
}

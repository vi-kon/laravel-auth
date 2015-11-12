<?php

namespace ViKon\Auth\Model;

use ViKon\Auth\Database\Eloquent\Model;

/**
 * Class Group
 *
 * @package ViKon\Auth\Model
 *
 * @author  Kovács Vince<vincekovacs@hotmail.com>
 *
 * @property int                                                                          $id
 * @property string                                                                       $token
 * @property boolean                                                                      $static
 * @property boolean                                                                      $hidden
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\User[]       $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\Role[]       $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\Permission[] $permissions
 *
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\Group whereToken($token)
 */
class Group extends Model
{
    const FIELD_ID     = 'id';
    const FIELD_TOKEN  = 'token';
    const FIELD_STATIC = 'static';
    const FIELD_HIDDEN = 'static';

    /**
     * {@inheritDoc}
     */
    public function __construct(array $attributes = [])
    {
        $this->table      = static::$config->get('vi-kon.auth.table.user_groups');
        $this->timestamps = false;
        $this->casts      = [
            static::FIELD_TOKEN => 'string',
        ];

        parent::__construct($attributes);
    }

    /** @noinspection ClassMethodNameMatchesFieldNameInspection */
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, static::$config->get('vi-kon.auth.table.rel__user__group'), 'group_id', 'user_id');
    }

    /** @noinspection ClassMethodNameMatchesFieldNameInspection */
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, static::$config->get('vi-kon.auth.table.rel__group__role'), 'group_id', 'role_id');
    }

    /** @noinspection ClassMethodNameMatchesFieldNameInspection */
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, static::$config->get('vi-kon.auth.table.rel__group__permission'), 'group_id', 'permission_id');
    }

}
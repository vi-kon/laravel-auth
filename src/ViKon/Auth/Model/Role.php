<?php

namespace ViKon\Auth\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 *
 * @package ViKon\Auth\Model
 *
 * @author  Kovács Vince<vincekovacs@hotmail.com>
 *
 * @property integer                                                                      $id
 * @property string                                                                       $token
 * @property boolean                                                                      $static
 * @property boolean                                                                      $hidden
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\User[]       $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\Group[]      $groups
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\Permission[] $permissions
 */
class Role extends Model
{
    const FIELD_ID     = 'id';
    const FIELD_TOKEN  = 'token';
    const FIELD_STATIC = 'static';
    const FIELD_HIDDEN = 'hidden';

    /**
     * {@inheritDoc}
     */
    public function __construct(array $attributes = [])
    {
        $this->table      = config('vi-kon.auth.table.user_roles');
        $this->timestamps = false;
        $this->casts      = [
            static::FIELD_TOKEN  => 'string',
            static::FIELD_STATIC => 'boolean',
            static::FIELD_HIDDEN => 'boolean',
        ];

        parent::__construct($attributes);
    }

    /** @noinspection ClassMethodNameMatchesFieldNameInspection */
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, config('vi-kon.auth.table.rel__user__role'), 'role_id', 'user_id');
    }

    /** @noinspection ClassMethodNameMatchesFieldNameInspection */
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, config('vi-kon.auth.table.rel__group__role'), 'role_id', 'group_id');
    }

    /** @noinspection ClassMethodNameMatchesFieldNameInspection */
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, config('vi-kon.auth.table.rel__role__permission'), 'role_id', 'permission_id');
    }
}

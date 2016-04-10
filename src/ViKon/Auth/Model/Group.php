<?php

namespace ViKon\Auth\Model;

use Illuminate\Database\Eloquent\Model;

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
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\User[]       $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\Role[]       $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\Permission[] $permissions
 */
class Group extends Model
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
        $this->table      = config('vi-kon.auth.table.user_groups');
        $this->timestamps = false;
        $this->casts      = [
            static::FIELD_TOKEN => 'string',
        ];

        parent::__construct($attributes);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, config('vi-kon.auth.table.rel__user__group'), 'group_id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, config('vi-kon.auth.table.rel__group__role'), 'group_id', 'role_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, config('vi-kon.auth.table.rel__group__permission'), 'group_id', 'permission_id');
    }

}
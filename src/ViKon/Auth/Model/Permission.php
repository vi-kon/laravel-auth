<?php

namespace ViKon\Auth\Model;

use ViKon\Auth\Database\Eloquent\Model;

/**
 * Class Permission
 *
 * @package ViKon\Auth\Model
 *
 * @author  Kovács Vince<vincekovacs@hotmail.com>
 *
 * @property integer                                                                $id
 * @property string                                                                 $token
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\User[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\Role[] $roles
 *
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\Permission whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\Permission whereToken($value)
 */
class Permission extends Model
{
    /**
     * {@inheritDoc}
     */
    public function __construct(array $attributes = [])
    {
        $this->table = static::$config->get('vi-kon.auth.table.user_permissions');;
        $this->timestamps = false;

        parent::__construct($attributes);
    }

    /** @noinspection ClassMethodNameMatchesFieldNameInspection */
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, static::$config->get('vi-kon.auth.table.rel__user__permission'), 'permission_id', 'user_id');
    }

    /** @noinspection ClassMethodNameMatchesFieldNameInspection */
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, static::$config->get('vi-kon.auth.table.rel__role__permission'), 'permission_id', 'role_id');
    }
}

<?php

namespace ViKon\Auth\Model;

use ViKon\Auth\Database\Eloquent\Model;

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
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\User[]       $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\Permission[] $permissions
 *
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\Role whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\Role whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\Role whereStatic($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\Role whereHidden($value)
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
        $this->table      = static::$config->get('vi-kon.auth.table.user_roles');
        $this->timestamps = false;

        parent::__construct($attributes);
    }

    /** @noinspection ClassMethodNameMatchesFieldNameInspection */
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, static::$config->get('vi-kon.auth.table.rel__user__role'), 'role_id', 'user_id');
    }

    /** @noinspection ClassMethodNameMatchesFieldNameInspection */
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, static::$config->get('vi-kon.auth.table.rel__role__permission'), 'role_id', 'permission_id');
    }
}

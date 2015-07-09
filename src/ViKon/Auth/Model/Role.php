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
    /**
     * {@inheritDoc}
     */
    public function __construct(array $attributes = [])
    {
        $this->table      = 'user_roles';
        $this->timestamps = false;

        parent::__construct($attributes);
    }

    /** @noinspection ClassMethodNameMatchesFieldNameInspection */
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'rel_user_role', 'role_id', 'user_id');
    }

    /** @noinspection ClassMethodNameMatchesFieldNameInspection */
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'rel_role_permission', 'role_id', 'permission_id');
    }
}

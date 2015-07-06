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
 * @property integer                                                                $id
 * @property string                                                                 $name
 * @property string                                                                 $token
 * @property string                                                                 $description
 * @property boolean                                                                $static
 * @property boolean                                                                $hidden
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\User[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\Role[] $roles
 *
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\Group whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\Group whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\Group whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\Group whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\Group whereStatic($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\Group whereHidden($value)
 */
class Group extends Model
{
    /**
     * {@inheritDoc}
     */
    public function __construct(array $attributes = [])
    {
        $this->table      = 'user_groups';
        $this->timestamps = false;

        parent::__construct($attributes);
    }

    /** @noinspection ClassMethodNameMatchesFieldNameInspection */
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'rel_user_group', 'group_id', 'user_id');
    }

    /** @noinspection ClassMethodNameMatchesFieldNameInspection */
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'rel_group_role', 'group_id', 'role_id');
    }
}

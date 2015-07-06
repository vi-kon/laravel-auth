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
 * @property integer                                                                 $id
 * @property string                                                                  $name
 * @property string                                                                  $description
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\User[]  $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Model\Group[] $groups
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\Role whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\Role whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\Role whereDescription($value)
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
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'rel_group_role', 'role_id', 'group_id');
    }
}

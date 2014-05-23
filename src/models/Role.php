<?php

namespace ViKon\Auth\models;

/**
 * ViKon\Auth\models\Role
 *
 * @property integer                                                                  $id
 * @property string                                                                   $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\models\User[]  $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\models\Group[] $groups
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\models\Role whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\models\Role whereName($value)
 */
class Role extends \Eloquent
{

    /**
     *
     * Disable updated_at and created_at columns
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_roles';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('ViKon\Auth\models\User', 'rel_user_role', 'role_id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany('ViKon\Auth\models\Group', 'rel_role_group', 'role_id', 'group_id');
    }
}

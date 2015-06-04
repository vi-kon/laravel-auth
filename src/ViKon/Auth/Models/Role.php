<?php

namespace ViKon\Auth\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ViKon\Auth\Models\Role
 *
 * @property integer                                                                  $id
 * @property string                                                                   $name
 * @property string                                                                   $description
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Models\User[]  $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Models\Group[] $groups
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Models\Role whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Models\Role whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Models\Role whereDescription($value)
 */
class Role extends Model
{

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
    protected $table = 'user_roles';

    /**
     * The database table used by the model (mongodb).
     *
     * @var string
     */
    protected $collection = 'user_roles';

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
        return $this->belongsToMany('ViKon\Auth\models\Group', 'rel_group_role', 'role_id', 'group_id');
    }
}

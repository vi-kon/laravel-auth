<?php

namespace ViKon\Auth\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ViKon\Auth\Models\Group
 *
 * @property integer                                                                 $id
 * @property string                                                                  $name
 * @property string                                                                  $token
 * @property string                                                                  $description
 * @property boolean                                                                 $static
 * @property boolean                                                                 $hidden
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Models\User[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\Models\Role[] $roles
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Models\Group whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Models\Group whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Models\Group whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Models\Group whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Models\Group whereStatic($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Models\Group whereHidden($value)
 */
class Group extends Model {
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
    protected $table = 'user_groups';

    /**
     * The database table used by the model (mongodb).
     *
     * @var string
     */
    protected $collection = 'user_groups';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users() {
        return $this->belongsToMany('ViKon\Auth\models\User', 'rel_user_group', 'group_id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles() {
        return $this->belongsToMany('ViKon\Auth\models\Role', 'rel_group_role', 'group_id', 'role_id');
    }
}

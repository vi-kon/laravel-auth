<?php

namespace ViKon\Auth\models;

use Illuminate\Database\Eloquent\Model;

/**
 * ViKon\Auth\models\Group
 *
 * @property integer                                                                 $id
 * @property string                                                                  $name
 * @property string                                                                  $token
 * @property string                                                                  $description
 * @property boolean                                                                 $static
 * @property boolean                                                                 $hidden
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\models\User[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\ViKon\Auth\models\Role[] $roles
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\models\Group whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\models\Group whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\models\Group whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\models\Group whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\models\Group whereStatic($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\models\Group whereHidden($value)
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

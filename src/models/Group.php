<?php

namespace ViKon\Auth\models;

class Group extends \Eloquent
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
    protected $table = 'user_groups';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('ViKon\Auth\models\User', 'rel_user_group', 'group_id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('ViKon\Auth\models\Role', 'rel_role_group', 'group_id', 'role_id');
    }
}

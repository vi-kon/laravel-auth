<?php

namespace ViKon\Auth\models;

class UserPasswordReminder extends \Eloquent
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
    protected $table = 'user_password_reminders';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('ViKon\Auth\models\User', 'id', 'user_id');
    }
}

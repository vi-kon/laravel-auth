<?php

namespace ViKon\Auth\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserPasswordReminder
 *
 * @package ViKon\Auth\Model
 *
 * @author  Kovács Vince<vincekovacs@hotmail.com>
 *
 * @property integer                     $id
 * @property integer                     $user_id
 * @property string                      $token
 * @property \Carbon\Carbon              $created_at
 *
 * @property-read \ViKon\Auth\Model\User $user
 */
class UserPasswordReminder extends Model
{
    const FIELD_ID         = 'id';
    const FIELD_USER_ID    = 'user_id';
    const FIELD_TOKEN      = 'token';
    const FIELD_CREATED_AT = 'created_at';

    /**
     * {@inheritDoc}
     */
    public function __construct(array $attributes = [])
    {
        $this->table      = config('vi-kon.auth.table.user_password_reminders');
        $this->timestamps = false;
        $this->casts      = [
            static::FIELD_USER_ID => 'integer',
            static::FIELD_TOKEN   => 'integer',
        ];
        $this->dates      = [
            static::FIELD_CREATED_AT,
        ];

        parent::__construct($attributes);
    }

    /** @noinspection ClassMethodNameMatchesFieldNameInspection */
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}

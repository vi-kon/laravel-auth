<?php

namespace ViKon\Auth\Model;

use ViKon\Auth\Eloquent\Model;

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
 * @property-read \ViKon\Auth\Model\User $user
 *
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\UserPasswordReminder whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\UserPasswordReminder whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\UserPasswordReminder whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\Auth\Model\UserPasswordReminder whereCreatedAt($value)
 */
class UserPasswordReminder extends Model
{
    /**
     * {@inheritDoc}
     */
    public function __construct(array $attributes = [])
    {
        $this->table      = 'user_password_reminders';
        $this->timestamps = false;

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

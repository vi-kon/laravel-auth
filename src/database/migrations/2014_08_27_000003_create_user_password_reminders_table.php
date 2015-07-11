<?php

use Illuminate\Database\Schema\Blueprint;
use ViKon\Auth\Database\Migration\Migration;

/**
 * Class CreateUserPasswordRemindersTable
 *
 * @author Kovács Vince<vincekovacs@hotmail.com>
 */
class CreateUserPasswordRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        static::$schema->create(static::$config->get('vi-kon.auth.table.user_password_reminders'), function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->string('token');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        static::$schema->drop(static::$config->get('vi-kon.auth.table.user_password_reminders'));
    }
}

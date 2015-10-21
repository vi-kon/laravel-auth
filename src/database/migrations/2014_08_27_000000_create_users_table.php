<?php

use Illuminate\Database\Schema\Blueprint;
use ViKon\Auth\Database\Migration\Migration;

/**
 * Class CreateUsersTable
 *
 * @author Kovács Vince<vincekovacs@hotmail.com>
 */
class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        static::$schema->create(static::$config->get('vi-kon.auth.table.users'), function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('username');
            $table->string('password');
            $table->string('email');
            $table->string('remember_token')
                  ->nullable(true)
                  ->default(null);
            $table->string('home')
                  ->nullable(true)
                  ->default(null);
            $table->string('namespace')
                  ->nullable(true)
                  ->default(null);
            $table->boolean('blocked')
                  ->default(false);
            $table->boolean('static')
                  ->default(false);
            $table->boolean('hidden')
                  ->default(false);
            $table->timestamps();

            $table->unique(['username', 'namespace']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        static::$schema->drop(static::$config->get('vi-kon.auth.table.users'));
    }
}

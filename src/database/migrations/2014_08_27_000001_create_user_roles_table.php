<?php

use Illuminate\Database\Schema\Blueprint;
use ViKon\Auth\Database\Migration\Migration;

/**
 * Class CreateUserRolesTable
 *
 * @author Kovács Vince<vincekovacs@hotmail.com>
 */
class CreateUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        static::$schema->create(static::$config->get('vi-kon.auth.table.user_roles'), function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('token')
                  ->nullable(true)
                  ->default(null)
                  ->unique();
            $table->boolean('static')
                  ->default(false);
            $table->boolean('hidden')
                  ->default(false);
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
        static::$schema->drop(static::$config->get('vi-kon.auth.table.user_roles'));
    }
}

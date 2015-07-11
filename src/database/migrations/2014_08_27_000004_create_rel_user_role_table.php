<?php

use Illuminate\Database\Schema\Blueprint;
use ViKon\Auth\Database\Migration\Migration;

/**
 * Class CreateRelUserRoleTable
 *
 * @author Kovács Vince<vincekovacs@hotmail.com>
 */
class CreateRelUserRoleTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        static::$schema->create(static::$config->get('vi-kon.auth.table.rel__user__role'), function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->unsignedInteger('role_id');
            $table->foreign('role_id')
                  ->references('id')
                  ->on('user_roles')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        static::$schema->drop(static::$config->get('vi-kon.auth.table.rel__user__role'));
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use ViKon\Auth\Database\Migration\Migration;

/**
 * Class CreateRelRolePermissionTable
 *
 * @author Kovács Vince<vincekovacs@hotmail.com>
 */
class CreateRelRolePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        static::$schema->create(static::$config->get('vi-kon.auth.table.rel__role__permission'), function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->unsignedInteger('role_id');
            $table->foreign('role_id')
                  ->references('id')
                  ->on('user_roles')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->unsignedInteger('permission_id');
            $table->foreign('permission_id')
                  ->references('id')
                  ->on('user_permissions')
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
        static::$schema->drop(static::$config->get('vi-kon.auth.table.rel__role__permission'));
    }
}

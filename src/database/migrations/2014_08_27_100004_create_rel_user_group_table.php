<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateRelRolePermissionTable
 *
 * @author Kovács Vince<vincekovacs@hotmail.com>
 */
class CreateRelUserGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $schema = app()->make('db')->connection()->getSchemaBuilder();

        $schema->create(config('vi-kon.auth.table.rel__user__group'), function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                  ->references('id')
                  ->on(config('vi-kon.auth.table.users'))
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->unsignedInteger('group_id');
            $table->foreign('group_id')
                  ->references('id')
                  ->on(config('vi-kon.auth.table.user_groups'))
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
        $schema = app()->make('db')->connection()->getSchemaBuilder();

        $schema->drop(config('vi-kon.auth.table.rel__user__group'));
    }
}

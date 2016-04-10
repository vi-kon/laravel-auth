<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateRelGroupPermissionTable
 *
 * @author Kovács Vince<vincekovacs@hotmail.com>
 */
class CreateRelGroupPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $schema = app()->make('db')->connection()->getSchemaBuilder();

        $schema->create(config('vi-kon.auth.table.rel__group__permission'), function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->unsignedInteger('group_id');
            $table->foreign('group_id')
                  ->references('id')
                  ->on(config('vi-kon.auth.table.user_groups'))
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->unsignedInteger('permission_id');
            $table->foreign('permission_id')
                  ->references('id')
                  ->on(config('vi-kon.auth.table.user_permissions'))
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

        $schema->drop(config('vi-kon.auth.table.rel__group__permission'));
    }
}

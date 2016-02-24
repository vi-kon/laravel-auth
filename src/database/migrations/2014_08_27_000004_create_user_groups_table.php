<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateUserGroupsTable
 *
 * @author Kovács Vince<vincekovacs@hotmail.com>
 */
class CreateUserGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $schema = app()->make('db')->connection()->getSchemaBuilder();

        $schema->create(config('vi-kon.auth.table.user_groups'), function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('token');
            $table->boolean('static')
                  ->default(false);
            $table->boolean('hidden')
                  ->default(false);
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

        $schema->drop(config('vi-kon.auth.table.user_groups'));
    }
}

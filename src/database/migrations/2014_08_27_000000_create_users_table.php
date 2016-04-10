<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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
        $schema = app()->make('db')->connection()->getSchemaBuilder();

        $schema->create(config('vi-kon.auth.table.users'), function (Blueprint $table) {
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
                  ->default('');
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
        $schema = app()->make('db')->connection()->getSchemaBuilder();

        $schema->drop(config('vi-kon.auth.table.users'));
    }
}

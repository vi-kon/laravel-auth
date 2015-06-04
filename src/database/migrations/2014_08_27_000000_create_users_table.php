<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
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
            $table->string('package')
                  ->default('system');
            $table->boolean('blocked')
                  ->default(false);
            $table->boolean('static')
                  ->default(false);
            $table->boolean('hidden')
                  ->default(false);
            $table->timestamps();

            $table->unique(['username', 'package']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}

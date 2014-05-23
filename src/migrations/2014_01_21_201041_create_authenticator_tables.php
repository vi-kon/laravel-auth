<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAuthenticatorTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('username')
                      ->unique();
                $table->string('password');
                $table->string('email');
                $table->string('remember_token')
                      ->nullable(true)
                      ->default(null);
                $table->string('home')
                      ->nullable(true)
                      ->default(null);
                $table->boolean('blocked')
                      ->default(false);
                $table->boolean('static')
                      ->default(false);
                $table->boolean('hidden')
                      ->default(false);
            }
        );

        Schema::create('user_roles', function (Blueprint $table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('name');
            }
        );

        Schema::create('user_groups', function (Blueprint $table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('name');
                $table->string('token')
                      ->nullable(true)
                      ->default(null)
                      ->unique();
                $table->boolean('static')
                      ->default(false);
                $table->boolean('hidden')
                      ->default(false);
            }
        );

        Schema::create('rel_role_group', function (Blueprint $table)
            {
                $table->engine = 'InnoDB';
                $table->integer('role_id')
                      ->unsigned();
                $table->foreign('role_id')
                      ->references('id')
                      ->on('user_roles')
                      ->onUpdate('cascade')
                      ->onDelete('cascade');
                $table->integer('group_id')
                      ->unsigned();
                $table->foreign('group_id')
                      ->references('id')
                      ->on('user_groups')
                      ->onUpdate('cascade')
                      ->onDelete('cascade');
            }
        );

        Schema::create('rel_user_role', function (Blueprint $table)
            {
                $table->engine = 'InnoDB';
                $table->integer('user_id')
                      ->unsigned();
                $table->foreign('user_id')
                      ->references('id')
                      ->on('users')
                      ->onUpdate('cascade')
                      ->onDelete('cascade');
                $table->integer('role_id')
                      ->unsigned();
                $table->foreign('role_id')
                      ->references('id')
                      ->on('user_roles')
                      ->onUpdate('cascade')
                      ->onDelete('cascade');
            }
        );

        Schema::create('rel_user_group', function (Blueprint $table)
            {
                $table->engine = 'InnoDB';
                $table->integer('user_id')
                      ->unsigned();
                $table->foreign('user_id')
                      ->references('id')
                      ->on('users')
                      ->onUpdate('cascade')
                      ->onDelete('cascade');
                $table->integer('group_id')
                      ->unsigned();
                $table->foreign('group_id')
                      ->references('id')
                      ->on('user_groups')
                      ->onUpdate('cascade')
                      ->onDelete('cascade');
            }
        );

        Schema::create('user_password_reminders', function (Blueprint $table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->integer('user_id')
                      ->unsigned();
                $table->foreign('user_id')
                      ->references('id')
                      ->on('users')
                      ->onUpdate('cascade')
                      ->onDelete('cascade');
                $table->string('token');
                $table->timestamp('created_at');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Schema::dropIfExists('user_password_reminders');
        Schema::dropIfExists('rel_role_group');
        Schema::dropIfExists('rel_user_role');
        Schema::dropIfExists('rel_user_group');
        Schema::dropIfExists('user_groups');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('users');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

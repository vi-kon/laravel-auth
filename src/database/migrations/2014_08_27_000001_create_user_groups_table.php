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
        Schema::create('user_groups', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('name');
            $table->string('token')
                  ->nullable(true)
                  ->default(null)
                  ->unique();
            $table->string('description', 1000);
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
        Schema::drop('user_groups');
    }
}

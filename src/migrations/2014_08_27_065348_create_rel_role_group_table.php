<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRelRoleGroupTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rel_role_group');
    }
}

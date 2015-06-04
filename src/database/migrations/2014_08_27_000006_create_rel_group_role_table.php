<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateRelGroupRoleTable
 *
 * @author Kovács Vince <kovacs.vince@sicontact.hu>
 */
class CreateRelGroupRoleTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rel_group_role', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->unsignedInteger('group_id');
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
        Schema::drop('rel_group_role');
    }
}

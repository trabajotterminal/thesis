<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlancesUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('glance_user', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned()->unique();
            $table->integer('user_id') -> unsigned();
            $table->integer('group_id') -> unsigned();
            $table->integer('school_id') -> unsigned();
            $table->integer('glance_id') -> unsigned();
            $table->integer('topic_id') -> unsigned();
            $table->integer('category_id') -> unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('group_id')->references('group_id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('school_id')->references('school_id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('glance_id')->references('id')->on('glances')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('category_id')->references('category_id')->on('topics')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('glance_user');
    }
}

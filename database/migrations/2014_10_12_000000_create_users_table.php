<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id')->unsigned()->unique();
            $table->string('name');
            $table->string('lastname');
            $table->string('password');
            $table->string('email') -> unique();
            $table->enum('type', ['alumno', 'administrador']);
            $table->integer('group_id') -> unsigned() -> nullable();
            $table->integer('school_id') -> unsigned() -> nullable();
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('school_id')->references('school_id')->on('groups')->onDelete('cascade')->onUpdate('cascade');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}

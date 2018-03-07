<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('glances', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned()->unique();
            $table->enum('type', ['C', 'S', 'T']);
            $table->integer('topic_id') -> unsigned();
            $table->integer('category_id') -> unsigned();
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
        Schema::dropIfExists('glances');
    }
}

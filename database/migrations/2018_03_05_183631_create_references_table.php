<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('references', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->enum('type', ['T', 'C', 'S', 'i']);
            $table->string('pending_route');
            $table->string('approved_route')->default('');
            $table->boolean('needs_approval')->default(true);
            $table->boolean('is_approval_pending')->default(false);
            $table->integer('uploaded_using_file')->default(true);
            $table->integer('topic_id')->unsigned();
            $table->integer('category_id')->unsigned();
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
        Schema::dropIfExists('references');
    }
}

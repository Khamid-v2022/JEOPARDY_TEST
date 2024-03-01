<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feature_task_questions', function (Blueprint $table) {
            $table->id();
            $table->integer('header_id');
            $table->string('category', 255)->nullable();
            $table->text('question')->nullable();
            $table->string('answer', 255)->nullable();
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
        Schema::dropIfExists('feature_task_questions');
    }
};

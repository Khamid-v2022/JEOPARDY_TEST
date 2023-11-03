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
        Schema::create('original_questions', function (Blueprint $table) {
            $table->id();
            $table->string('category', 255)->nullable();
            $table->string('value', 20)->nullable();
            $table->text('question')->nullable();
            $table->string('answer', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('original_questions');
    }
};

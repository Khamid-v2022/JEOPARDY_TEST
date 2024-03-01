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
        Schema::create('user_answer_headers', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('score')->default(0);
            $table->integer('number_of_questions')->default(50);
            $table->tinyInteger('test_type')->default(1);           // 0: general pricing test, 1: Free trial test, 2: Featured Test
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
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
        Schema::dropIfExists('user_answer_headers');
    }
};

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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30)->nullable();
            $table->string('email', 50)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('address', 255)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('zipcode', 20)->nullable();
            $table->string('country', 50)->nullable();
            $table->integer('default_question_count')->default(50);
            $table->string('password', 50);
            $table->tinyInteger('is_trial_used')->default(0);
            $table->tinyInteger('subscription_status')->default(0);
            $table->enum('subscription_plan', ['Monthly', 'Annually'])->default('Annually');
            $table->timestamp('subscribed_at')->nullable();
            $table->timestamp('expire_at')->nullable();
            $table->tinyInteger('is_delete')->default(0);
            $table->timestamp('deleted_at')->nullable();
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
};

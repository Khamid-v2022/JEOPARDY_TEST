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
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('plan_id', 255);
            $table->string('stripe_customer_id', 50);
            $table->string('stripe_plan_price_id', 255)->nullable()->default(NULL);
            $table->string('stripe_payment_intent_id', 50);
            $table->string('stripe_subscription_id', 50);
            $table->string('default_payment_method', 255)->nullable()->default(NULL);
            $table->string('default_source', 255)->nullable()->default(NULL);
            $table->float('paid_amount', 10, 2);
            $table->string('paid_amount_currency', 10);
            $table->string('plan_interval', 10);
            $table->tinyInteger('plan_interval_count')->default(1);
            $table->string('customer_name', 50)->nullable()->default(NULL);
            $table->string('customer_email', 50)->nullable()->default(NULL);
            $table->timestamp('plan_period_start')->nullable()->default(NULL);
            $table->timestamp('plan_period_end')->nullable()->default(NULL);
            $table->string('status', 50);
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
        Schema::dropIfExists('user_subscriptions');
    }
};

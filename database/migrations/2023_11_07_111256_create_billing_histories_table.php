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
        Schema::create('billing_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('card_number', 255)->nullable();
            $table->string('method', 20)->nullable();
            $table->string('package', 20)->nullable();
            $table->float('amount', 8, 2);
            $table->string('period', 20)->nullable();
            $table->text('reference')->nullable();
            $table->string('transaction_id', 255)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->string('detail_name', 255)->nullable();
            $table->string('detail_address', 255)->nullable();
            $table->string('detail_city', 255)->nullable();
            $table->string('detail_zipcode', 255)->nullable();
            $table->string('detail_country', 255)->nullable();
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
        Schema::dropIfExists('billing_histories');
    }
};

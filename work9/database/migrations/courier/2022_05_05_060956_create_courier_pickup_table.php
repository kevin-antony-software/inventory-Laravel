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
        Schema::create('courier_pickup', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('courier_customer_id');
            $table->string('courier_customer_name');
            $table->string('model')->nullable();
            $table->string('warranty')->nullable();
            $table->string('status');
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
        Schema::dropIfExists('courier_pickup');
    }
};

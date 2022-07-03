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
        Schema::create('writeoffs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('invoice_id')->nullable();
            $table->bigInteger('job_id')->nullable();
            $table->string('customer_name');
            $table->string('type');
            $table->decimal('amount', 15, 2)->nullable();
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
        Schema::dropIfExists('writeoffs');
    }
};

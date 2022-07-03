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
        Schema::create('invoice_payment', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('payment_id');
            $table->dateTime('payment_date');
            $table->string('payment_method');
            $table->bigInteger('cheque_id')->nullable();
            $table->bigInteger('invoice_id')->nullable();
            $table->dateTime('invoice_date')->nullable();
            $table->bigInteger('customer_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('commission_owner')->nullable();
            $table->bigInteger('job_id')->nullable();
            $table->dateTime('job_closed_date')->nullable();
            $table->decimal('amount', 15, 2)->unsigned()->default(0);
            $table->bigInteger('days')->nullable();
            $table->bigInteger('commission_percentage')->nullable();
            $table->decimal('commission', 15, 2)->nullable();
			$table->decimal('balance', 15, 2)->unsigned()->default(0);
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
        Schema::dropIfExists('invoice_payment');
    }
};

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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('user_name');
            $table->string('status');
            $table->string('method');
            $table->decimal('totalAmount', 15, 2);
            $table->decimal('allocatedToInvoice', 15, 2);
            $table->decimal('balanceToAllocate', 15, 2);
            $table->bigInteger('customer_id');
            $table->string('customer_name');
            $table->bigInteger('bank_id')->nullable();
            $table->string('bank_name')->nullable();
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
        Schema::dropIfExists('payments');
    }
};

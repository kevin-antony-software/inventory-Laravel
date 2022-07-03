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
        Schema::create('bank_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bank_id');
            $table->string('bank_name');
            $table->bigInteger('payment_id')->nullable();
            $table->bigInteger('expense_id')->nullable();
            $table->decimal('amount', 15, 2)->unsigned()->default(0);
            $table->decimal('debit', 15, 2)->unsigned()->nullable();
            $table->decimal('credit', 15, 2)->unsigned()->nullable();
            $table->decimal('bankBalance', 15, 2)->unsigned()->default(0);
            $table->string('reason')->nullable();
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
        Schema::dropIfExists('bank_details');
    }
};

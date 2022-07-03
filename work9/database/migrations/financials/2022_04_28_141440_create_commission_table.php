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
        Schema::create('commission', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('owner_id');
            $table->string('owner_name');
            $table->integer('month');
            $table->integer('year');
            $table->string('status');
            $table->decimal('totalCommission', 15, 2)->nullable();
            $table->decimal('paidCommission', 15, 2)->nullable();
            $table->decimal('returnChequeCommission', 15, 2)->nullable();
            $table->decimal('invoiceDueAmount', 15, 2)->nullable();
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
        Schema::dropIfExists('commission');
    }
};

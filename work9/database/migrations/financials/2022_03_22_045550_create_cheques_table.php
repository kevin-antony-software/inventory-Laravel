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
        Schema::create('cheques', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('payment_id');
            $table->bigInteger('number');
            $table->bigInteger('bank');
            $table->bigInteger('branch');
            $table->decimal('amount', 15, 2)->unsigned()->default(0);
            $table->date('chequeDate');
            $table->bigInteger('customer_id');
            $table->string('customer_name');
            $table->string('status');
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
        Schema::dropIfExists('cheques');
    }
};

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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('VATnumber')->nullable();
            $table->string('BRnumber')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('city')->nullable();
            $table->double('creditLimit', 15, 2)->unsigned()->default(0);
            $table->double('chequeLimit', 15, 2)->unsigned()->default(0);
            $table->bigInteger('owner_ID')->nullable();
			$table->string('owner_name')->nullable();
			$table->string('customer_type');
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
        Schema::dropIfExists('customers');
    }
};

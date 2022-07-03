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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('to');
            $table->date('actualDate');
            $table->string('category');
            $table->string('method');
            $table->string('description');
            $table->decimal('amount', 15, 2);
            $table->integer('bank_ID')->nullable();
            $table->string('bank_name')->nullable();
            $table->integer('user_ID');
            $table->string('user_name');
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
        Schema::dropIfExists('expenses');
    }
};

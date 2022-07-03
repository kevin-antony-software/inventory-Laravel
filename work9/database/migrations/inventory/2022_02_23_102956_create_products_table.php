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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->biginteger('category_id');
            $table->string('category_name');
            $table->double('USDcost', 15, 2);
            $table->double('ExchangeUSDRate', 15, 2);
            $table->double('firstCost', 15, 2);
            $table->double('DFP', 15, 2);
            $table->double('totalcost', 15, 2);
            $table->double('price', 15, 2);
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
        Schema::dropIfExists('products');
    }
};

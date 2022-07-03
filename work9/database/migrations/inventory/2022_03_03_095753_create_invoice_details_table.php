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
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('invoice_id');
            $table->bigInteger('product_id');
            $table->string('product_name');
            $table->decimal('qty', 15, 2)->unsigned()->default(0);
            $table->decimal('price', 15, 2)->unsigned()->default(0);
            $table->decimal('discountPercentage', 15, 2)->unsigned()->default(0);
            $table->decimal('priceAfterDiscount', 15, 2)->unsigned()->default(0);
            $table->decimal('subtotal_price', 15, 2)->unsigned()->default(0);
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
        Schema::dropIfExists('invoice_details');
    }
};

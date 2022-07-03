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
        Schema::create('return_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id');
            $table->string('customer_name');
            $table->bigInteger('invoice_id');
            $table->bigInteger('warehouse_id');
            $table->string('warehouse_name');
            $table->bigInteger('product_id');
            $table->string('product_name');
            $table->integer('qty');
            $table->decimal('price_of_each', 15, 2);
            $table->decimal('old_total', 15, 2);
            $table->decimal('new_total', 15, 2);
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
        Schema::dropIfExists('return_items');
    }
};

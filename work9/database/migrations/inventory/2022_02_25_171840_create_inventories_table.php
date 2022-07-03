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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->biginteger('product_id');
            $table->string('product_name');
            $table->double('price', 15, 2);
            $table->double('cost', 15, 2);
            $table->biginteger('category_id');
            $table->string('category_name');
            $table->biginteger('warehouse_id');
            $table->string('warehouse_name');
            $table->integer('qty');
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
        Schema::dropIfExists('inventories');
    }
};

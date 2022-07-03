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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id');
            $table->string('customer_name');
            $table->bigInteger('warehouse_id');
            $table->string('warehouse_name');
            $table->decimal('total', 15, 2)->unsigned()->default(0);
            $table->decimal('vatAmount', 15, 2)->unsigned()->default(0);
            $table->decimal('payed', 15, 2)->unsigned()->default(0);
            $table->decimal('dueAmount', 15, 2)->unsigned()->default(0);
            $table->decimal('totalCommision', 15, 2)->unsigned()->default(0);
            $table->bigInteger('commission_user_ID');
            $table->string('commission_owner');
            $table->bigInteger('user_ID');
            $table->string('user_name');
            $table->string('status');
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
        Schema::dropIfExists('invoices');
    }
};

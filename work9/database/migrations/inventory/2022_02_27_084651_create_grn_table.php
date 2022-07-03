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
        Schema::create('grn', function (Blueprint $table) {
            $table->id();
            $table->integer('FromWarehouse_ID');
            $table->string('FromWarehouse_name');
            $table->integer('ToWarehouse_ID');
            $table->string('ToWarehouse_name');
            $table->integer('user_ID');
            $table->string('user_name');
            $table->string('status');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grn');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id');
            $table->string('customer_name');
            $table->string('serialNum')->nullable();
            $table->bigInteger('repairTimes');
            $table->date('soldDate')->nullable();
            $table->string('model');
            $table->string('promptIn')->nullable();
            $table->string('machineType');
            $table->string('warranty');
            $table->string('jobStatus');

            $table->double('duration', 8, 2)->nullable();

            $table->bigInteger('jobStartUser_id')->nullable();
            $table->string('jobStartUser_name')->nullable();
            $table->dateTime('jobStartTime', 0)->nullable();
            

            $table->double('componentCharges', 8, 2)->nullable();
            $table->double('estimatedCost', 8, 2)->nullable();
            $table->bigInteger('jobClosedUser_id')->nullable();
            $table->string('jobClosedUser_name')->nullable();
            $table->dateTime('jobClosedTime', 0)->nullable();

            $table->double('repairCharges', 8, 2)->nullable();
            $table->double('totalCharges', 8, 2)->nullable();
            $table->double('discount', 8, 2)->nullable();
            $table->double('finalTotal', 8, 2)->nullable();
            $table->double('dueAmount', 8, 2)->nullable();
            $table->double('PaidAmount', 8, 2)->nullable();
            $table->string('payment_status')->nullable();


            $table->dateTime('deliveredDate', 0)->nullable();
            $table->string('promptOut')->nullable();
            $table->string('comment')->nullable();
            $table->string('issue')->nullable();
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
        Schema::dropIfExists('jobs');
    }
}

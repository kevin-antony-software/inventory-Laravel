<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageUTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_u', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('job_id');
            $table->string('imagepath1');
            $table->string('imagepath2');
            $table->string('imagepath3');
            $table->string('imagepath4');
            $table->string('imagepath5');
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
        Schema::dropIfExists('image_u');
    }
}

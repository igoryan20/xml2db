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
        Schema::create('concrete_car_characteristics', function (Blueprint $table) {
            $table->id('concrete_car_id');
            $table->year('year');
            $table->integer('run');
            $table->string('color');
            $table->string('body_type');
            $table->string('engine_type');
            $table->string('transmission');
            $table->string('gear_type');
            $table->foreignId('generation_id')->nullable();
            $table->foreignId('model_id');

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
        Schema::dropIfExists('concrete_car_characteristics');
    }
};

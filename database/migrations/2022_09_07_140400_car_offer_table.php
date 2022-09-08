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
        Schema::create('car_offers', function (Blueprint $table) {
            $table->id('offer_id');
            $table->year('year');
            $table->integer('run')->nullable();
            $table->string('color')->nullable();
            $table->string('body_type');
            $table->string('engine_type');
            $table->string('transmission');
            $table->string('gear_type');
            $table->bigInteger('generation_id')->nullable()->unsigned();
            $table->bigInteger('model_id')->unsigned();

            $table->foreign('generation_id')->references('generation_id')->on('car_generations');
            $table->foreign('model_id')->references('model_id')->on('car_models');

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
        Schema::dropIfExists('car_offers');
    }
};

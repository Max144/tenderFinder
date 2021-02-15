<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialDimensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_dimensions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('width');
            $table->integer('length');
            $table->float('price');

            $table->unsignedInteger('material_thickness_id');
            $table->foreign('material_thickness_id')
                ->references('id')->on('material_thicknesses')->onDelete('cascade');
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
        Schema::dropIfExists('material_dimensions');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeThicknessFloatInMaterialThicknessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('material_thicknesses', function (Blueprint $table) {
            $table->float('thickness')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('material_thicknesses', function (Blueprint $table) {
            $table->integer('thickness')->change();
        });
    }
}

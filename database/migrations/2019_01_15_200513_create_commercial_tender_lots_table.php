<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommercialTenderLotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commercial_tender_lots', function (Blueprint $table) {
            $table->increments('id');

            $table->string('lot');
            $table->unsignedInteger('success_commercial_tender_id');
            $table->foreign('success_commercial_tender_id')->references('id')->on('success_commercial_tenders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commercial_tender_lots');
    }
}

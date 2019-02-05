<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGovernmentTenderLotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('government_tender_lots', function (Blueprint $table) {
            $table->increments('id');

            $table->string('lot');
            $table->unsignedInteger('success_government_tender_id');
            $table->foreign('success_government_tender_id')->references('id')->on('success_government_tenders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('government_tender_lots');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenderLotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tender_lots', function (Blueprint $table) {
            $table->increments('id');

            $table->text('lot')->nullable();
            $table->unsignedInteger('success_tender_id');
            $table->foreign('success_tender_id')->references('id')->on('success_tenders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tender_lots');
    }
}

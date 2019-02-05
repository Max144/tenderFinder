<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuccessCommercialTendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('success_commercial_tenders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tender_name')->nullable;

            $table->unsignedInteger('tender_id');
            $table->foreign('tender_id')->references('id')->on('commercial_tenders')->onDelete('cascade');

            $table->boolean('new')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('success_commercial_tenders');
    }
}

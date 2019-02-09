<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteCommercialAndGovernmentTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('government_tender_lots');
        Schema::dropIfExists('commercial_tender_lots');
        Schema::dropIfExists('success_government_tenders');
        Schema::dropIfExists('success_commercial_tenders');
        Schema::dropIfExists('government_tenders');
        Schema::dropIfExists('commercial_tenders');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeTenderNameAndLotsTextFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('success_government_tenders', function (Blueprint $table) {
            $table->text('tender_name')->nullable()->change();
        });
        Schema::table('success_commercial_tenders', function (Blueprint $table) {
            $table->text('tender_name')->nullable()->change();
        });
        Schema::table('government_tender_lots', function (Blueprint $table) {
            $table->text('lot')->nullable()->change();
        });
        Schema::table('commercial_tender_lots', function (Blueprint $table) {
            $table->text('lot')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('success_government_tenders', function (Blueprint $table) {
            Schema::table('success_government_tenders', function (Blueprint $table) {
                $table->string('tender_name')->nullable()->change();
            });
            Schema::table('success_commercial_tenders', function (Blueprint $table) {
                $table->string('tender_name')->nullable()->change();
            });
            Schema::table('government_tender_lots', function (Blueprint $table) {
                $table->string('lot')->nullable()->change();
            });
            Schema::table('commercial_tender_lots', function (Blueprint $table) {
                $table->string('lot')->nullable()->change();
            });
        });
    }
}

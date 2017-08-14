<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToSitesAndPagesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sites', function ($table) {
            $table->integer('is_site_changed')->after('paid_till');
        });

        Schema::table('pages', function ($table) {
            $table->integer('is_page_changed')->after('url_page');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sites', function ($table) {
            $table->dropColumn('is_site_changed');
        });

        Schema::table('pages', function ($table) {
            $table->dropColumn('is_page_changed');
        });
    }
}

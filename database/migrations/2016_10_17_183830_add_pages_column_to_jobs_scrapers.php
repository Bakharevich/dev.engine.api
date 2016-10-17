<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPagesColumnToJobsScrapers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs_scrapers', function (Blueprint $table) {
            $table->string('pages')->after('url')->default(1)->comment('Amount of pages to scrape');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs_scrapers', function (Blueprint $table) {
            $table->dropColumn('pages');
        });
    }
}

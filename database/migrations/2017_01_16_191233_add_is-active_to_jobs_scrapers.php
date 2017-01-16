<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsActiveToJobsScrapers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs_scrapers', function(Blueprint $table) {
            $table->tinyInteger('is_active')->default(0)->after('amount_executed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs_scrapers', function(Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
}

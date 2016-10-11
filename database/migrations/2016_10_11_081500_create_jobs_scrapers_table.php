<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsScrapersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs_scrapers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id');
            $table->string('scraper');
            $table->integer('category_id');
            $table->text('url');
            $table->dateTime('executed_at')->default(\Carbon\Carbon::now());
            $table->integer('amount_executed')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs_scrapers');
    }
}

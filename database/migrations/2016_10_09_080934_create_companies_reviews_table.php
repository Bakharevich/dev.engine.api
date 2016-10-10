<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies_reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index()->default(0);
            $table->integer('company_id')->index();
            $table->string('name')->default('');
            $table->text('review');
            $table->float('rating');
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
        Schema::dropIfExists('companies_reviews');
    }
}

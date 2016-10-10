<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->default(0)->index();
            $table->integer('category_id')->default(0)->index();
            $table->string('name')->default('')->nullable();
            $table->string('address')->default('')->nullable();
            $table->string('tel')->default('')->nullable();
            $table->string('website')->default('')->nullable();
            $table->string('main_photo')->default('')->nullable();
            $table->string('main_photo_url')->default('')->nullable();
            $table->text('description')->nullable();
            $table->string('latitude')->default(0)->nullable();
            $table->string('longitude')->default(0)->nullable();
            $table->string('synonyms')->default('')->nullable();
            $table->string('domain')->default('')->index();
            $table->string('url')->default('');
            $table->text('last_review')->comment('Last user review about company')->nullable();
            $table->string('original_url')->default('')->comment('Url where we found company')->nullable();
            $table->float('rating')->default(0)->nullable();
            $table->tinyInteger('price_range')->default(0)->nullable();
            $table->integer('amount_comments')->default(0)->nullable();
            $table->timestamps();

            $table->unique(['site_id', 'domain']);
            $table->index(['site_id', 'domain']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}

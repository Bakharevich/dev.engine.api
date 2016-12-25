<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id');
            $table->integer('city_id');
            $table->integer('category_id');
            $table->text('title');
            $table->text('description');
            $table->text('content');
            $table->tinyInteger('is_disabled');
            $table->string('meta_keywords');
            $table->string('meta_description');
            $table->string('photo');
            $table->string('domain');
            $table->string('url');
            $table->timestamps();

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
        Schema::dropIfExists('news');
    }
}

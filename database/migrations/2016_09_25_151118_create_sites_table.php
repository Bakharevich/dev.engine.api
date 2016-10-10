<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('country_id');
            $table->integer('city_id');
            $table->string('name');
            $table->string('media_url')->default('')->comment('Url to domain where all media is located');
            $table->string('media_path')->default('')->comment('Server path to media directory');
            $table->string('domain')->index();
            $table->tinyInteger('menu_type')->default(1);
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
        Schema::dropIfExists('sites');
    }
}

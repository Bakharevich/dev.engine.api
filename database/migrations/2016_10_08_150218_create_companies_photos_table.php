<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies_photos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->index();
            $table->string('filename');
            $table->string('url')->comment('Full url to image');
            $table->integer('pos')->default(0);
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
        Schema::dropIfExists('companies_photos');
    }
}

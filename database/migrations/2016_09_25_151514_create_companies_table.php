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
            $table->string('name')->default('');
            $table->string('address')->default('');
            $table->string('tel')->default('');
            $table->string('website')->default('');
            $table->string('main_photo')->default('');
            $table->string('main_photo_url')->default('');
            $table->text('description');
            $table->string('latitude')->default(0);
            $table->string('longitude')->default(0);
            $table->string('synonyms')->default('');
            $table->string('domain')->default('')->index();
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

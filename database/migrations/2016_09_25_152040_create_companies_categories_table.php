<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->index();
            $table->integer('category_group_id')->index();
            $table->string('name');
            $table->string('description_top');
            $table->string('description_bottom');
            $table->string('domain')->index();
            $table->string('icon');
            $table->string('url')->default('');
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
        DB::statement('SET foreign_key_checks = 0');
        Schema::dropIfExists('categories');
        DB::statement('SET foreign_key_checks = 1');
    }
}

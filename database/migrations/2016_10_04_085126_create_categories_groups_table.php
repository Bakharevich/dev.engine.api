<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->index();
            $table->string('name1');
            $table->string('name2');
            $table->timestamps();
        });

        Schema::create('category_category_group', function (Blueprint $table) {
            $table->integer('category_id')->unsigned()->index();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            $table->integer('category_group_id')->unsigned()->index();
            $table->foreign('category_group_id')->references('id')->on('categories_groups')->onDelete('cascade');

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
        DB::statement('SET foreign_key_checks = 0');

        Schema::dropIfExists('categories_groups');
        Schema::dropIfExists('category_category_group');

        DB::statement('SET foreign_key_checks = 1');
    }
}

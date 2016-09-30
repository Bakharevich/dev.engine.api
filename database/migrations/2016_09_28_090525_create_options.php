<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('option_group_id')->index();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('company_option', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->index();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');

            $table->integer('option_id')->unsigned()->index();
            $table->foreign('option_id')->references('id')->on('options')->onDelete('cascade');

            $table->timestamps();
        });

//        Schema::create('category_option', function (Blueprint $table) {
//            $table->integer('category_id')->unsigned()->index();
//            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
//
//            $table->integer('option_id')->unsigned()->index();
//            $table->foreign('option_id')->references('id')->on('options')->onDelete('cascade');
//
//            $table->timestamps();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET foreign_key_checks = 0');

        Schema::dropIfExists('options');
        Schema::dropIfExists('company_option');
        //Schema::dropIfExists('category_option');

        DB::statement('SET foreign_key_checks = 1');
    }
}

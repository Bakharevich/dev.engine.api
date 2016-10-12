<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meta', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->index();
            $table->string('url');
            $table->string('title')->default('');
            $table->string('description')->default('');
            $table->string('keywords')->default('');
            $table->string('image')->default('');
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
        Schema::dropIfExists('meta');
    }
}

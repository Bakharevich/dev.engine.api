<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCityIdToCategoriesGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories_groups', function(Blueprint $table) {
            $table->integer('city_id')->default(0)->after('site_id');

            $table->dropIndex('categories_groups_site_id_index');
            $table->index(['site_id', 'city_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories_groups', function(Blueprint $table) {
            $table->dropColumn('city_id');

            $table->dropIndex('categories_groups_site_id_city_id_index');
            $table->index(['site_id']);
        });
    }
}

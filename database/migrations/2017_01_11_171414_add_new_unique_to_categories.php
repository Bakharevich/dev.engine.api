<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewUniqueToCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            // drop old unique
            $table->dropUnique('categories_site_id_domain_unique');

            // add new unique
            $table->unique(['site_id', 'city_id', 'domain']);
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

        Schema::table('categories', function (Blueprint $table) {
            // drop new unique
            $table->dropUnique('categories_site_id_city_id_domain_unique');

            // return old unique
            $table->unique(['site_id', 'domain']);
        });

        DB::statement('SET foreign_key_checks = 1');
    }
}

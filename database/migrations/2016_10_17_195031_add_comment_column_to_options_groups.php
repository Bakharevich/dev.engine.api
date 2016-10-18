<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommentColumnToOptionsGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('options_groups', function (Blueprint $table) {
            $table->string('comment')->after('icon')->default('')->comment('Any comment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('options_groups', function (Blueprint $table) {
            $table->dropColumn('comment');
        });
    }
}

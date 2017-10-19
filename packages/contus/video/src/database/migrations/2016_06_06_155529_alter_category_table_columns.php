<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCategoryTableColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function(Blueprint $table) {
            $table->string('slug');
            $table->bigInteger('parent_id')->default(0);
            $table->string('level');
            $table->bigInteger('is_leaf_category')->default(0);
            $table->dropColumn('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function(Blueprint $table) {
            $table->dropColumn('slug');
            $table->dropColumn('parent_id');
            $table->dropColumn('level');
            $table->dropColumn('is_leaf_category');
            $table->string('value');
        });
    }
}

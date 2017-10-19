<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class AddColumnCategoryOrderCategoryTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table ( 'categories', function ($table) {
            $table->integer ( 'category_order' )->after ( 'is_active' )->default ( 0 );
        } );
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table ( 'categories', function ($table) {
            $table->dropColumn ( 'category_order' );
        } );
    }
}

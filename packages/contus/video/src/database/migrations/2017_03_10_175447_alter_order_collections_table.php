<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class AlterOrderCollectionsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table ( 'collections', function (Blueprint $table) {
            $table->integer ( 'order' )->default ( 0 )->after ( 'is_active' );
        } );
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table ( 'collections', function (Blueprint $table) {
            $table->dropColumn ( 'order' );
        } );
    }
}

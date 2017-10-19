<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class AddColumnTrailerDataType extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table ( 'videos', function ($table) {
            $table->dropColumn ( 'trailer' );
        } );
        
        Schema::table('videos', function($table){
           $table->tinyInteger('trailer_status')->default(0)->after('notification_status');
         });
        
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        
        Schema::table ( 'videos', function ($table) {
            $table->text( 'trailer' );
        } );
        Schema::table ( 'videos', function ($table) {
            $table->dropColumn ( 'trailer_status' );
        } );
    }
}

<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToLiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table ( 'videos', function ($table) {
            $table->string ( 'broadcast_location' )->default ( null );
            $table->string ( 'stream_id' )->default ( null );
            $table->string ( 'source_url' )->default ( null );
            $table->string ( 'encoder_type' )->default ( null );
            $table->string ( 'hosted_page_url' )->default ( null );
            $table->string ( 'username' )->default ( null );
            $table->string ( 'password' )->default ( null );
            $table->string ( 'stream_name' )->default ( null );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table ( 'videos', function ($table) {
            $table->dropColumn ( 'stream_name' );
			$table->dropColumn ( 'password' );
			$table->dropColumn ( 'username' );
			$table->dropColumn ( 'hosted_page_url' );
			$table->dropColumn ( 'encoder_type' );
			$table->dropColumn ( 'source_url' );
			$table->dropColumn ( 'stream_id' );
			$table->dropColumn ( 'broadcast_location' );
        } );
    }
}

<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateVideoFavouriteTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create ( 'favourite_videos', function (Blueprint $table) {
			$table->increments ( 'id' );
			$table->bigInteger ( 'customer_id' )->unsigned ();
			$table->foreign ( 'customer_id' )->references ( 'id' )->on ( 'customers' );
			$table->bigInteger ( 'video_id' )->unsigned ();
			$table->foreign ( 'video_id' )->references ( 'id' )->on ( 'videos' )->onDelete ( 'cascade' );
			$table->timestamps ();
		} );
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop ( 'favourite_videos' );
	}
}

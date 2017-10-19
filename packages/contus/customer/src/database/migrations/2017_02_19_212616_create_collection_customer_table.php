<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create ( 'customer_collections', function (Blueprint $table) {
			$table->increments ( 'id' );
			$table->bigInteger ( 'customer_id' )->unsigned ();
			$table->foreign ( 'customer_id' )->references ( 'id' )->on ( 'customers' );
			$table->bigInteger ( 'collection_id' )->unsigned ();
			$table->foreign ( 'collection_id' )->references ( 'id' )->on ( 'collections' )->onDelete ( 'cascade' );
			$table->timestamps ();
		} );
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop ( 'customer_collections' );
    }
}

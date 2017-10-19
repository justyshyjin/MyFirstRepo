<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreatePaymentMethodsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create ( 'payment_methods', function (Blueprint $table) {
            $table->bigIncrements ( 'id' );
            $table->string ( 'name', 255 );
            $table->string ( 'type', 255 );
            $table->string ( 'slug', 255 );
            $table->text ( 'description' );
            $table->tinyInteger ( 'is_test' )->default ( 0 );
            $table->tinyInteger ( 'is_active' )->default ( 0 );
            $table->bigInteger ( 'creator_id' )->default ( 0 );
            $table->bigInteger ( 'updator_id' )->default ( 0 );
            $table->timestamps ();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop ( 'payment_methods' );
    }
}

<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreatePaymentSettingsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create ( 'payment_settings', function (Blueprint $table) {
            $table->bigIncrements ( 'id' );
            $table->bigInteger ( 'payment_method_id' )->unsigned ();
            $table->string('slug',255);
            $table->text('key');
            $table->text('value');
            $table->tinyInteger ( 'is_test' )->default ( 0 );
            $table->bigInteger ( 'creator_id' )->default ( 0 );
            $table->bigInteger ( 'updator_id' )->default ( 0 );
            $table->timestamps ();
        } );

        Schema::table ( 'payment_settings', function ($table) {
            $table->foreign ( 'payment_method_id' )->references ( 'id' )->on ( 'payment_methods' )->onDelete ( 'cascade' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop ( 'payment_settings' );
    }
}

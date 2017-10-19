<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create ( 'notifications', function (Blueprint $table) {
            $table->bigIncrements ( 'id' );
            $table->bigInteger ( 'customer_id' )->unsigned ()->default ( 0 );
            $table->bigInteger ( 'user_id' )->unsigned ()->default ( 0 );
            $table->text('content');
            $table->string('type',255);
            $table->bigInteger ( 'type_id' );
            $table->tinyInteger ( 'is_read' )->default ( 0 );
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
    public function down()
    {
        Schema::drop ( 'notifications' );
    }
}

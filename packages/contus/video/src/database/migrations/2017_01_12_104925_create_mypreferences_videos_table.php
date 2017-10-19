<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMypreferencesVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create ( 'mypreferences_videos', function (Blueprint $table) {
            $table->bigIncrements ( 'id' );
            $table->string( 'category_id' )->nallable ();
            $table->string( 'type' )->nallable();
            $table->string( 'user_id' )->nallable();
            $table->string ( 'order' )->nallable ();
            $table->tinyInteger ( 'is_active' )->default (1);
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
        
        Schema::drop ( 'mypreferences_videos' );;
    }
}

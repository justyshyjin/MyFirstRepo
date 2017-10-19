<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoQuestionanswers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {    Schema::create ( 'video_questionanswers', function (Blueprint $table) {
            $table->bigIncrements ( 'id' );
            $table->bigInteger ( 'video_id' )->unsigned ();
            $table->string('user_type');
            $table->bigInteger ( 'user_id' )->unsigned ();
            $table->bigInteger ( 'customer_id' )->unsigned ();
            $table->text('questions');
            $table->bigInteger ( 'creator_id' )->default ( 0 );
            $table->bigInteger ( 'updator_id' )->default ( 0 );
            $table->timestamps ();
            $table->foreign ( 'video_id' )->references ( 'id' )->on ( 'videos' )->onDelete ( 'cascade' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::drop ( 'video_questionanswers' );
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoReplyQuestionanswers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   Schema::create ( 'video_reply_questionanswers', function (Blueprint $table) {
            $table->bigIncrements ( 'id' );
            $table->bigInteger ( 'questions_id' )->unsigned ();
            $table->string('user_type');
            $table->bigInteger ( 'user_id' )->unsigned ();
            $table->bigInteger ( 'customer_id' )->unsigned ();
            $table->text('answers');
            $table->bigInteger ( 'creator_id' )->default ( 0 );
            $table->bigInteger ( 'updator_id' )->default ( 0 );
            $table->timestamps ();
            $table->foreign ( 'questions_id' )->references ( 'id' )->on ( 'video_questionanswers' )->onDelete ( 'cascade' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::drop ( 'video_reply_questionanswers' );
    }
}

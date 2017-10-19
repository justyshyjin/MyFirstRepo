<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVideoReplyQuestionanswersAddQuestionIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table ( 'video_reply_questionanswers', function (Blueprint $table) {
             $table->bigInteger ( 'question_id' )->unsigned ();
             $table->foreign ( 'question_id' )->references ( 'id' )->on ( 'video_questionanswers' )->onDelete ( 'cascade' );
           
         } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         $table->dropColumn ( 'question_id' );
    }
}

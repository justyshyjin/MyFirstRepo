<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVideoReplyQuestionanswersQuestionsIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table ( 'video_reply_questionanswers', function (Blueprint $table) {
             $table->dropForeign('video_reply_questionanswers_questions_id_foreign');
             $table->dropColumn('questions_id');
           
         } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

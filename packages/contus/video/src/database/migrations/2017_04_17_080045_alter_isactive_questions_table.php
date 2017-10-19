<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterIsactiveQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table ( 'video_questionanswers', function ($table) {
            $table->integer ( 'is_active' )->after ( 'questions' )->default ( 0 );
			$table->integer ( 'is_read' )->after ( 'questions' )->default ( 0 );
			$table->integer ( 'is_notify' )->after ( 'questions' )->default ( 0 );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table ( 'video_questionanswers', function ($table) {
            $table->dropColumn ( 'is_active' );
			$table->dropColumn ( 'is_read' );
			$table->dropColumn ( 'is_notify' );
        } );
    }
}

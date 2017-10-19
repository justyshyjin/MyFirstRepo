<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterIsactiveCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table ( 'comments', function ($table) {
            $table->integer ( 'is_active' )->after ( 'comment' )->default ( 0 );
			$table->integer ( 'is_read' )->after ( 'comment' )->default ( 0 );
			$table->integer ( 'is_notify' )->after ( 'comment' )->default ( 0 );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table ( 'comments', function ($table) {
            $table->dropColumn ( 'is_active' );
			$table->dropColumn ( 'is_read' );
			$table->dropColumn ( 'is_notify' );
        } );
    }
}

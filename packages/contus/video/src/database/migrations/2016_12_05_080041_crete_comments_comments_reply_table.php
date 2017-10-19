<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreteCommentsCommentsReplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create ( 'comments', function (Blueprint $table) {
            $table->bigIncrements ( 'id' );
            $table->bigInteger ( 'video_id' )->unsigned ();
            $table->string('user_type');
            $table->bigInteger ( 'user_id' )->unsigned ();
            $table->bigInteger ( 'customer_id' )->unsigned ();
            $table->text('comment');
            $table->bigInteger ( 'creator_id' )->default ( 0 );
            $table->bigInteger ( 'updator_id' )->default ( 0 );
            $table->timestamps ();
            $table->foreign ( 'video_id' )->references ( 'id' )->on ( 'videos' )->onDelete ( 'cascade' );
        } );
        Schema::create ( 'reply_comments', function (Blueprint $table) {
            $table->bigIncrements ( 'id' );
            $table->bigInteger ( 'comment_id' )->unsigned ();
            $table->string('user_type');
            $table->bigInteger ( 'user_id' )->unsigned ();
            $table->bigInteger ( 'customer_id' )->unsigned ();
            $table->text('comment');
            $table->bigInteger ( 'creator_id' )->default ( 0 );
            $table->bigInteger ( 'updator_id' )->default ( 0 );
            $table->timestamps ();
            $table->foreign ( 'comment_id' )->references ( 'id' )->on ( 'comments' )->onDelete ( 'cascade' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop ( 'reply_comments' );
        Schema::drop ( 'comments' );
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactUsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('contact_us', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',255);
            $table->string('phone',255);
            $table->string('email',255);
            $table->longText('message');
            $table->tinyInteger('is_active')->default(1);
            $table->bigInteger('creator_id')->default(0);
            $table->bigInteger('updator_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::drop ( 'contact_us' );
    }
}

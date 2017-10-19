<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',100);
            $table->string('email',100);
            $table->string('password', 100);
            $table->string('phone', 50);
            $table->string('acesstype', 50);
            $table->string('google_auth_id');
            $table->string('google_user_id');
            $table->string('facebook_auth_id');
            $table->string('facebook_user_id');
            $table->string('access_token');
            $table->text('avatar');
            $table->string('access_otp_token');
            $table->enum('login_type', array('Web', 'Android', 'iOS', 'FB', 'Google+'));
            $table->tinyInteger('is_active')->default(0);
            $table->bigInteger('creator_id')->default(0);
            $table->bigInteger('updator_id')->default(0);
            $table->date('expires_at')->nullable();
            $table->softDeletes();
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
       Schema::drop ( 'customers' );
    }
}

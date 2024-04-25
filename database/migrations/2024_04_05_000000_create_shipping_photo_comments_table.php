<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingPhotoCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_photo_comments', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->string('user_name');
            $table->string('user_email');
            $table->text('attachment_url');
            $table->boolean('email_sent');            
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
        Schema::dropIfExists('shipping_photo_comments');
    }
}

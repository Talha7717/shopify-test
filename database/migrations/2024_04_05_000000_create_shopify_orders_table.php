<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopifyOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopify_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->text('order_price');
            $table->text('order_create');
            $table->text('defualt_address');
            $table->text('shipping_address');
            $table->text('billing_address');
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

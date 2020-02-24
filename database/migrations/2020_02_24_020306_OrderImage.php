<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_order_image', function (Blueprint $table) {
            $table->increments('image_id');
            $table->string('image', 300);
            $table->dateTime('created_at');
            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('order_id')->on('qc_orders');
        });
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

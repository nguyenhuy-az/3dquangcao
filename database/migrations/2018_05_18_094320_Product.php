<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Product extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_products', function(Blueprint $table)
        {
            $table->increments('product_id');
            $table->string('nameCode',30)->unique();
            $table->integer('width');
            $table->integer('height');
            $table->integer('depth')->nullable;
            $table->integer('price');
            $table->integer('amount');
            $table->string('description',300);
            $table->tinyInteger('finishStatus')->default(0);
            $table->tinyInteger('cancelStatus')->default(0);
            $table->dateTime('created_at');
            $table->integer('type_id')->unsigned();
            $table->foreign('type_id')->references('type_id')->on('qc_product_type');
            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('order_id')->on('qc_orders');
            //$table->timestamps();
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

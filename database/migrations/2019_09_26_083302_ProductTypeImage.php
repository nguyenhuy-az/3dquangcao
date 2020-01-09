<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductTypeImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_product_type_image', function(Blueprint $table)
        {
            $table->increments('image_id');
            $table->string('name', 250);
            $table->dateTime('created_at');
            $table->integer('type_id')->unsigned();
            $table->foreign('type_id')->references('type_id')->on('qc_product_type');
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

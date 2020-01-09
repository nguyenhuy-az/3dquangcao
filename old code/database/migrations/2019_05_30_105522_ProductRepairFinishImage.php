<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductRepairFinishImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_product_repair_finish_image', function(Blueprint $table)
        {
            $table->increments('image_id');
            $table->string('name',300); // ten hinh anh
            $table->dateTime('created_at');
            $table->integer('finish_id')->unsigned();
            $table->foreign('finish_id')->references('finish_id')->on('qc_product_repair_finish');
            //$table->rememberToken();
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

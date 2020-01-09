<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductCancel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_product_cancel', function(Blueprint $table)
        {
            $table->increments('cancel_id');
            $table->text('reason');
            $table->date('cancelDate');
            $table->dateTime('created_at');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('product_id')->on('qc_products');
            $table->integer('staff_id')->unsigned();
            $table->foreign('staff_id')->references('staff_id')->on('qc_staffs');
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

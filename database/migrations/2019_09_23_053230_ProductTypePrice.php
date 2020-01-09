<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductTypePrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_product_type_price', function(Blueprint $table)
        {
            $table->increments('price_id');
            $table->integer('price');
            $table->dateTime('applyDate');
            $table->tinyInteger('action')->default(0);
            $table->dateTime('created_at');
            $table->integer('type_id')->unsigned();
            $table->foreign('type_id')->references('type_id')->on('qc_product_type');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('company_id')->on('qc_companies');
            $table->integer('staff_id')->unsigned();
            $table->foreign('staff_id')->references('staff_id')->on('qc_staffs');
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

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductConstructionRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_product_construction_relation', function(Blueprint $table)
        {
            $table->increments('relation_id');
            $table->dateTime('created_at');
            $table->integer('product_id')->unsigned(); //lam san pham
            $table->foreign('product_id')->references('product_id')->on('qc_products');
            $table->integer('staff_id')->unsigned(); // nhan vien nhan
            $table->foreign('staff_id')->references('staff_id')->on('qc_staffs');
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

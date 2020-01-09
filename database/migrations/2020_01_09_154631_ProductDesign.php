<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductDesign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_product_design', function(Blueprint $table)
        {
            $table->increments('design_id');
            $table->string('image', 300);
            $table->text('description')->nullable();
            $table->tinyInteger('applyStatus')->default(0);
            $table->tinyInteger('confirmStatus')->default(0);
            $table->dateTime('confirmDate')->nullable();
            $table->dateTime('created_at');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('product_id')->on('qc_products');
            $table->integer('staff_id')->unsigned();
            $table->foreign('staff_id')->references('staff_id')->on('qc_staffs')->onDelete('cascade');
            $table->integer('confirmStaff_id')->unsigned()->nullable();
            $table->foreign('confirmStaff_id')->references('staff_id')->on('qc_staffs')->onDelete('cascade');
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

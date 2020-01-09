<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StaffSales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_staff_sales', function(Blueprint $table)
        {
            $table->increments('sales_id');
            $table->dateTime('beginDate');
            $table->dateTime('endDate');
            $table->tinyInteger('confirmStatus')->default(0);
            $table->tinyInteger('agreeStatus')->default(0);
            $table->tinyInteger('action')->default(1);
            $table->dateTime('created_at');
            $table->integer('level_id')->unsigned();
            $table->foreign('level_id')->references('level_id')->on('qc_sales_level')->onDelete('cascade');
            $table->integer('staff_id')->unsigned();
            $table->foreign('staff_id')->references('staff_id')->on('qc_staffs')->onDelete('cascade');
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

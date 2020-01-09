<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WorkAllocationReportImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_work_allocation_report_image', function(Blueprint $table)
        {
            $table->increments('image');
            $table->string('name',300); // ten hinh anh
            $table->dateTime('created_at');
            $table->integer('report_id')->unsigned();
            $table->foreign('report_id')->references('report_id')->on('qc_work_allocation_report');
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

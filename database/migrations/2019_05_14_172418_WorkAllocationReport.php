<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WorkAllocationReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_work_allocation_report', function(Blueprint $table)
        {
            $table->increments('report_id');
            $table->dateTime('reportDate');
            $table->text('content')->nullable(); // ghi chu
            $table->dateTime('created_at');
            $table->integer('allocation_id')->unsigned();
            $table->foreign('allocation_id')->references('allocation_id')->on('qc_work_allocation');
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

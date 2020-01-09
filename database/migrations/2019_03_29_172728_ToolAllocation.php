<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ToolAllocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_tool_allocation', function(Blueprint $table)
        {
            $table->increments('allocation_id');
            $table->dateTime('allocationDate');
            $table->tinyInteger('confirmStatus')->default(0);
            $table->dateTime('created_at');
            $table->integer('allocationStaff_id')->unsigned();
            $table->foreign('allocationStaff_id')->references('staff_id')->on('qc_staffs');
            $table->integer('receiveStaff_id')->unsigned();
            $table->foreign('receiveStaff_id')->references('staff_id')->on('qc_staffs');
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

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ToolReturn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_tool_return', function(Blueprint $table)
        {
            $table->increments('return_id');
            $table->dateTime('returnDate');
            $table->dateTime('created_at');
            $table->integer('returnStaff_id')->unsigned();
            $table->foreign('returnStaff_id')->references('staff_id')->on('qc_staffs');
            $table->integer('confirmStaff_id')->unsigned();
            $table->foreign('confirmStaff_id')->references('staff_id')->on('qc_staffs');
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

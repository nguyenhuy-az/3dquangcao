<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ToolStaff extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_tool_staff', function(Blueprint $table)
        {
            $table->increments('detail_id');
            $table->integer('amount');
            $table->dateTime('addDate');
            $table->dateTime('created_at');
            $table->integer('tool_id')->unsigned();
            $table->foreign('tool_id')->references('tool_id')->on('qc_tools');
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

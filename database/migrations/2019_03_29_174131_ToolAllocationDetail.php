<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ToolAllocationDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_tool_allocation_detail', function(Blueprint $table)
        {
            $table->increments('detail_id');
            $table->integer('amount');
            $table->tinyInteger('newStatus')->default(1);
            $table->dateTime('created_at');
            $table->integer('tool_id')->unsigned();
            $table->foreign('tool_id')->references('tool_id')->on('qc_tools');
            $table->integer('allocation_id')->unsigned();
            $table->foreign('allocation_id')->references('allocation_id')->on('qc_tool_allocation');
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

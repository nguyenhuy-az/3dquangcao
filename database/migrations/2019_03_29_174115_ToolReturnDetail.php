<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ToolReturnDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_tool_return_detail', function(Blueprint $table)
        {
            $table->increments('detail_id');
            $table->integer('amount');
            $table->tinyInteger('useStatus')->default(1);
            $table->dateTime('created_at');
            $table->integer('tool_id')->unsigned();
            $table->foreign('tool_id')->references('tool_id')->on('qc_tools');
            $table->integer('return_id')->unsigned();
            $table->foreign('return_id')->references('return_id')->on('qc_tool_return');
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

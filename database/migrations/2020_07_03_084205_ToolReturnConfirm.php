<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ToolReturnConfirm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_tool_return_confirm', function(Blueprint $table)
        {
            $table->increments('confirm_id');
            $table->integer('amount');
            $table->dateTime('created_at');
            $table->integer('store_id')->unsigned();
            $table->foreign('store_id')->references('store_id')->on('qc_company_store')->onDelete('cascade');
            $table->integer('return_id')->unsigned();
            $table->foreign('return_id')->references('return_id')->on('qc_tool_return')->onDelete('cascade');
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

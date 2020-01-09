<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImportDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_import_detail', function(Blueprint $table)
        {
            $table->increments('detail_id');
            $table->integer('price');
            $table->integer('amount');
            $table->integer('totalMoney');
            $table->dateTime('created_at');
            $table->integer('import_id')->unsigned();
            $table->foreign('import_id')->references('import_id')->on('qc_import');
            $table->integer('tool_id')->unsigned();
            $table->foreign('tool_id')->references('tool_id')->on('qc_tools');
            $table->integer('supplies_id')->unsigned();
            $table->foreign('supplies_id')->references('supplies_id')->on('qc_supplies');
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

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CompanyStore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_company_store', function(Blueprint $table)
        {
            $table->increments('store_id');
            $table->integer('amount');
            $table->dateTime('updateDate');
            $table->dateTime('created_at');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('company_id')->on('qc_companies');
            $table->integer('tool_id')->unsigned()->nullable();
            $table->foreign('tool_id')->references('tool_id')->on('qc_tools');
            $table->integer('supplies_id')->unsigned()->nullable();
            $table->foreign('supplies_id')->references('supplies_id')->on('qc_supplies');
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

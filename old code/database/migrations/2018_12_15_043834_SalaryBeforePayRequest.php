<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SalaryBeforePayRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_salary_before_pay_request', function(Blueprint $table)
        {
            $table->increments('request_id');
            $table->integer('money');
            $table->tinyInteger('agreeStatus')->default(0);
            $table->tinyInteger('transferStatus')->default(0);
            $table->text('conformNote');
            $table->tinyInteger('confirmStatus')->default(0);
            $table->dateTime('confirmDate');
            $table->dateTime('created_at');
            $table->integer('work_id')->unsigned();
            $table->foreign('work_id')->references('work_id')->on('qc_works')->onDelete('cascade');
            $table->integer('staffConfirm_id')->unsigned()->nullable();
            $table->foreign('staffConfirm_id')->references('staff_id')->on('qc_staffs')->onDelete('cascade');
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

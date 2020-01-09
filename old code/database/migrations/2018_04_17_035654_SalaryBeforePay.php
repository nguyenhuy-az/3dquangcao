<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SalaryBeforePay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_salary_before_pay', function(Blueprint $table)
        {
            $table->increments('pay_id');
            $table->integer('money');
            $table->dateTime('datePay');
            $table->string('description',30);
            $table->dateTime('created_at');
            $table->integer('work_id')->unsigned();
            $table->foreign('work_id')->references('work_id')->on('qc_works')->onDelete('cascade');
            $table->integer('staffPay_id')->unsigned();
            $table->foreign('staffPay_id')->references('staff_id')->on('qc_staffs')->onDelete('cascade');
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

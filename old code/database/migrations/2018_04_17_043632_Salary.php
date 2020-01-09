<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Salary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_salary', function(Blueprint $table)
        {
            $table->increments('salary_id');
            $table->integer('mainMinute');
            $table->integer('plusMinute');
            $table->integer('minusMinute');
            $table->integer('beforePay');
            $table->integer('minusMoney');
            $table->integer('benefitMoney');
            $table->integer('salary');
            $table->tinyInteger('payStatus')->default(0);
            $table->dateTime('created_at');
            $table->integer('work_id')->unsigned();
            $table->foreign('work_id')->references('work_id')->on('qc_works')->onDelete('cascade');
            $table->integer('salaryBasic_id')->unsigned();
            $table->foreign('salaryBasic_id')->references('salary_basic_id')->on('qc_staff_salary_basics')->onDelete('cascade');
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

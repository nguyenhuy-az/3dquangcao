<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class KeepMoneyPay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_keep_money_pay', function(Blueprint $table)
        {
            $table->increments('pay_id');
            $table->integer('money');
            $table->dateTime('payDate');
            $table->tinyInteger('confirmStatus')->default(0);
            $table->dateTime('created_at');
            $table->integer('keep_id')->unsigned();
            $table->foreign('keep_id')->references('keep_id')->on('qc_keep_money')->onDelete('cascade');
            $table->integer('staff_id')->unsigned();
            $table->foreign('staff_id')->references('staff_id')->on('qc_staffs')->onDelete('cascade');
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

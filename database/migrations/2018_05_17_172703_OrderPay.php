<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderPay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_order_pay', function(Blueprint $table)
        {
            $table->increments('pay_id');
            $table->integer('money');
            $table->string('note',225);
            $table->date('datePay');
            $table->dateTime('created_at');
            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('order_id')->on('qc_orders');
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

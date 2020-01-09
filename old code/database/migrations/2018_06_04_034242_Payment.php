<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Payment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_payment', function(Blueprint $table)
        {
            $table->increments('payment_id');
            $table->string('paymentCode',30)->unique();
            $table->integer('money');
            $table->dateTime('datePay');
            $table->string('note',300);
            $table->dateTime('created_at');
            $table->integer('type_id')->unsigned();
            $table->foreign('type_id')->references('type_id')->on('qc_payment_type');
            $table->integer('staff_id')->unsigned();
            $table->foreign('staff_id')->references('staff_id')->on('qc_staffs');
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

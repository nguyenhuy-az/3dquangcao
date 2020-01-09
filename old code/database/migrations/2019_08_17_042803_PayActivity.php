<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PayActivity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_pay_activity', function(Blueprint $table)
        {
            $table->increments('pay_id');
            $table->string('payCode',30)->unique();
            $table->integer('money');
            $table->dateTime('payDate');
            $table->string('note',300);
            $table->tinyInteger('type')->default(1);
            $table->dateTime('created_at');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('company_id')->on('qc_companies');
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

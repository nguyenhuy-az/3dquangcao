<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class QcOrderNotifyNew extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_order_notify_new', function(Blueprint $table)
        {
            $table->increments('notify_id');
            $table->tinyInteger('viewStatus')->default(0);
            $table->dateTime('viewDate')->nullable();
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

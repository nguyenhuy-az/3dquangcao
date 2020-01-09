<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Order extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_orders', function(Blueprint $table)
        {
            $table->increments('order_id');
            $table->string('nameCode',20)->unique();
            $table->string('name',30);
            $table->integer('totalPrice');
            $table->integer('discount')->nullable();
            $table->integer('payment');
            $table->string('design',225);
            $table->date('receiveDate');
            $table->date('deliveryDate');
            $table->date('finishDate',100);
            $table->tinyInteger('finishStatus')->default(0);
            $table->tinyInteger('action')->default(1);
            $table->dateTime('created_at');
            $table->integer('customer_id')->unsigned();
            $table->foreign('customer_id')->references('customer_id')->on('qc_customers');
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

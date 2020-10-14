<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderBonusBudget extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_order_bonus_budget', function (Blueprint $table) {
            $table->increments('budget_id');
            $table->dateTime('created_at');
            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('order_id')->on('qc_orders')->onDelete('cascade');
            $table->integer('bonus_id')->unsigned();
            $table->foreign('bonus_id')->references('bonus_id')->on('qc_bonus_department')->onDelete('cascade');;
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

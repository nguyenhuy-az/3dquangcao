<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TansfersDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_transfers_detail', function(Blueprint $table)
        {
            $table->increments('detail_id');
            $table->dateTime('created_at');
            $table->integer('transfers_id')->unsigned();
            $table->foreign('transfers_id')->references('transfers_id')->on('qc_transfers')->onDelete('cascade');
            $table->integer('pay_id')->unsigned();
            $table->foreign('pay_id')->references('pay_id')->on('qc_order_pay')->onDelete('cascade');
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

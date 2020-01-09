<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Transfers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_transfers', function(Blueprint $table)
        {
            $table->increments('transfers_id');
            $table->string('transfersCode',30)->unique();
            $table->integer('money');
            $table->dateTime('transfersDate');
            $table->string('reason',300);
            $table->dateTime('created_at');
            $table->integer('transfersStaff_id')->unsigned();
            $table->foreign('transfersStaff_id')->references('staff_id')->on('qc_staffs');
            $table->integer('receiveStaff_id')->unsigned();
            $table->foreign('receiveStaff_id')->references('staff_id')->on('qc_staffs');
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

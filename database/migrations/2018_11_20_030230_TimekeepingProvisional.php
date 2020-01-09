<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TimekeepingProvisional extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_timekeeping_provisional', function (Blueprint $table) {
            $table->increments('timekeeping_provisional_id');
            $table->dateTime('timeBegin');
            $table->dateTime('timeEnd')->nullable();
            $table->text('note')->nullable();
            $table->tinyInteger('afternoonStatus')->default(0);
            $table->tinyInteger('workStatus')->default(0);
            $table->tinyInteger('accuracyStatus')->default(0);
            $table->tinyInteger('confirmStatus')->default(0);
            $table->dateTime('created_at');
            $table->integer('work_id')->unsigned();
            $table->foreign('work_id')->references('work_id')->on('qc_works')->onDelete('cascade');
            $table->integer('staffConfirm_id')->unsigned();
            $table->foreign('staffConfirm_id')->references('staff_id')->on('qc_staffs')->onDelete('cascade');
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

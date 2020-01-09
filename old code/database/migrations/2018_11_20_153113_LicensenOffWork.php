<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LicensenOffWork extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_license_off_works', function (Blueprint $table) {
            $table->increments('license_id');
            $table->dateTime('dateOff');
            $table->text('note')->nullable();
            $table->tinyInteger('agreeStatus')->default(0);
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

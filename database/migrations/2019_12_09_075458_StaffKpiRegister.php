<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StaffKpiRegister extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_staff_kpi_register', function(Blueprint $table)
        {
            $table->increments('register_id');
            $table->dateTime('registerDate');
            $table->dateTime('applyDate');
            $table->tinyInteger('confirmDate')->default(0);
            $table->tinyInteger('confirmStatus')->default(0);
            $table->tinyInteger('agreeStatus')->default(0);
            $table->tinyInteger('action')->default(1);
            $table->dateTime('created_at');
            $table->integer('kpi_id')->unsigned();
            $table->foreign('kpi_id')->references('kpi_id')->on('qc_kpi')->onDelete('cascade');
            $table->integer('staff_id')->unsigned();
            $table->foreign('staff_id')->references('staff_id')->on('qc_staffs')->onDelete('cascade');
            $table->integer('confirmStaff_id')->unsigned()->nullable();
            $table->foreign('confirmStaff_id')->references('staff_id')->on('qc_staffs')->onDelete('cascade');
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

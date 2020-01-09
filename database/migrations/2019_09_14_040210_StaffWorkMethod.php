<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StaffWorkMethod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_staff_work_method', function(Blueprint $table)
        {
            $table->increments('method_id');
            $table->tinyInteger('method')->default(1); # 1 - lam viec chinh thuc / 2 - lam viec khong chinh thuc
            $table->tinyInteger('applyRule')->default(1); # ap dung noi quy phat: 1 - có / 2 - khong
            $table->tinyInteger('action')->default(1); # trang thai ap dung 1 - co / 0 - khong
            $table->dateTime('created_at');
            $table->integer('staff_id')->unsigned();
            $table->foreign('staff_id')->references('staff_id')->on('qc_staffs')->onDelete('cascade');
            $table->integer('confirmStaff_id')->unsigned();
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

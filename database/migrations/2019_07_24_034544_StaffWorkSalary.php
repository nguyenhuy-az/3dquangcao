<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StaffWorkSalary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_staff_work_salary', function(Blueprint $table)
        {
            $table->increments('workSalary_id');
            $table->integer('salary')->default(0); # l??ng co ban
            $table->integer('responsibility')->default(0); # tien phu cap trach nhiem  - VND
            $table->integer('usePhone')->default(0); # phu cap su dung dien thoai - VND
            $table->integer('insurance')->default(0); # phu cap bao hiem - %
            $table->integer('fuel')->default(0); # phu cap xang - VND
            $table->integer('dateOff')->default(0); # phu cap ngay nghi - ngay
            $table->integer('overtimeHour')->default(0); # phu cap an uong tang ca - VND
            $table->tinyInteger('action')->default(1);
            $table->dateTime('created_at');
            $table->integer('work_id')->unsigned();
            $table->foreign('work_id')->references('staff_id')->on('qc_staffs')->onDelete('cascade');
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

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StaffWorkSkill extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_staff_work_skill', function (Blueprint $table) {
            $table->increments('skill_id');
            $table->tinyInteger('level')->default(1);
            $table->tinyInteger('action')->default(1);
            $table->dateTime('created_at');
            $table->integer('work_id')->unsigned();
            $table->foreign('work_id')->references('work_id')->on('qc_department_work')->onDelete('cascade');
            $table->integer('staff_id')->unsigned();
            $table->foreign('staff_id')->references('staff_id')->on('qc_staffs')->onDelete('cascade');
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

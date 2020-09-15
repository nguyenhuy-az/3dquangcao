<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class JobApplicationInterview extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_job_application_interview', function (Blueprint $table) {
            $table->increments('interview_id');
            $table->tinyInteger('interviewConfirm')->default(0);
            $table->dateTime('interviewDate');
            $table->tinyInteger('agreeStatus')->default(0);
            $table->tinyInteger('action')->default(1);
            $table->dateTime('created_at');
            $table->integer('staff_id')->unsigned()->nullable();
            $table->foreign('staff_id')->references('staff_id')->on('qc_staffs')->onDelete('cascade');
            $table->integer('jobApplication_id')->unsigned();
            $table->foreign('jobApplication_id')->references('jobApplication_id')->on('qc_job_application')->onDelete('cascade');
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

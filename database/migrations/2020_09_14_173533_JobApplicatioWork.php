<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class JobApplicatioWork extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_job_application_work', function (Blueprint $table) {
            $table->increments('detail_id');
            $table->tinyInteger('skillStatus')->default(1);
            $table->dateTime('created_at');
            $table->integer('work_id')->unsigned();
            $table->foreign('work_id')->references('work_id')->on('qc_department_work')->onDelete('cascade');
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

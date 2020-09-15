<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class JobApplication extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_job_application', function(Blueprint $table)
        {
            $table->increments('jobApplication_id');
            $table->string('nameCode',20)->unique();
            $table->string('firstName',30);
            $table->string('lastName',20);
            $table->string('identityCard',20);
            $table->string('birthday',100);
            $table->tinyInteger('gender');
            $table->string('image',255);
            $table->string('identityFront', 255);
            $table->string('identityBack', 255);
            $table->string('email',100);
            $table->string('address',255);
            $table->string('phone',30);
            $table->text('introduce')->nullable();
            $table->integer('salaryOffer');
            $table->tinyInteger('confirmStatus')->default(0);
            $table->text('confirmNote')->nullable();
            $table->dateTime('confirmDate')->nullable();
            $table->tinyInteger('agreeStatus')->default(0);
            $table->tinyInteger('action')->default(1);
            $table->dateTime('created_at');
            $table->integer('confirmStaff_id')->unsigned()->nullable();
            $table->foreign('confirmStaff_id')->references('staff_id')->on('qc_staffs');
            //$table->rememberToken();
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

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SystemDateOff extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_system_date_off', function(Blueprint $table)
        {
            $table->increments('dateOff_id');
            $table->dateTime('dateOff',100);
            $table->string('description',300);
            $table->tinyInteger('type')->default(1);
            $table->dateTime('created_at');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('company_id')->on('qc_companies');
            $table->integer('staff_id')->unsigned();
            $table->foreign('staff_id')->references('staff_id')->on('qc_staffs');
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

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OverTimeRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_over_time_request', function (Blueprint $table) {
            $table->increments('request_id');
            $table->dateTime('requestDate');
            $table->text('note');
            $table->tinyInteger('acceptStatus')->default(0);
            $table->dateTime('created_at');
            $table->integer('work_id')->unsigned();
            $table->foreign('work_id')->references('work_id')->on('qc_company_staff_work')->onDelete('cascade');
            $table->integer('requestStaff_id')->unsigned();
            $table->foreign('requestStaff_id')->references('staff_id')->on('qc_staffs')->onDelete('cascade');

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

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ToolPackageAllocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_tool_package_allocation', function(Blueprint $table)
        {
            $table->increments('allocation_id');
            $table->dateTime('allocationDate'); // ngay giao
            $table->tinyInteger('allocationNumber')->default(1); // so lan giao
            $table->tinyInteger('confirmStatus')->default(0);
            $table->dateTime('confirmDate')->nullable();
            $table->tinyInteger('action')->default(1); // so lan giao
            $table->dateTime('created_at');
            $table->integer('package_id')->unsigned();
            $table->foreign('package_id')->references('package_id')->on('qc_tool_package')->onDelete('cascade');
            $table->integer('work_id')->unsigned();
            $table->foreign('work_id')->references('work_id')->on('qc_company_staff_work')->onDelete('cascade');
            $table->integer('staff_id')->unsigned();
            $table->foreign('staff_id')->references('staff_id')->on('qc_staffs')->onDelete('cascade');
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

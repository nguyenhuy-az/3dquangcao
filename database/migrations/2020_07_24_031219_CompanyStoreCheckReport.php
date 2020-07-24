<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CompanyStoreCheckReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_company_store_check_report', function(Blueprint $table)
        {
            $table->increments('report_id');
            $table->tinyInteger('reportStatus')->default(0); // trang thai bao cao dung cu
            $table->dateTime('reportDate'); // ngay bao cao
            $table->tinyInteger('confirmStatus')->default(0);  // nhan vien quan ly xac nhan
            $table->dateTime('confirmDate')->nullable(); // Ngay xac nhan
            $table->tinyInteger('confirmRight')->default(0); // bao cao dung / sai
            $table->text('confirmNote')->nullable(); // ghi chu xac nhan
            $table->dateTime('created_at');
            $table->integer('store_id')->unsigned();
            $table->foreign('store_id')->references('store_id')->on('qc_company_store')->onDelete('cascade');
            $table->integer('check_id')->unsigned();
            $table->foreign('check_id')->references('check_id')->on('qc_company_store_check')->onDelete('cascade');
            $table->integer('confirmStaff_id')->unsigned()->nullable(); // nguoi xac nhan
            $table->foreign('confirmStaff_id')->references('staff_id')->on('qc_staffs')->onDelete('cascade');
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

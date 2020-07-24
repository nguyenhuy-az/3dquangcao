<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CompanyStoreCheck extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_company_store_check', function(Blueprint $table)
        {
            $table->increments('check_id');
            $table->tinyInteger('confirmStatus')->default(0); // da kiem tra hay chua
            $table->dateTime('confirmDate')->nullable(); // thoi gian xac nha kiem tra
            $table->dateTime('receiveDate'); // ngay nhan kiem tra
            $table->tinyInteger('receiveStatus')->default(0); // trang thai nhan trong vong kiem tra
            $table->dateTime('created_at');
            $table->integer('staff_id')->unsigned(); // nguoi kiem tra
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

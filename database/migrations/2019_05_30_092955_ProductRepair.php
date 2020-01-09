<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductRepair extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_product_repair', function(Blueprint $table)
        {
            $table->increments('repair_id');
            $table->dateTime('beginDate'); // ngay nhan
            $table->dateTime('deadline'); // han sua chua
            $table->tinyInteger('repairType')->default(1); // trang thai sua; 0 - het bao hanh; 1 - con bao hanh
            $table->text('noted');
            $table->tinyInteger('action')->default(1); // 0 - da ket thuc / 1 - dang hoat dong
            $table->dateTime('created_at');
            $table->integer('product_id')->unsigned()->nullable(); //lam san pham
            $table->foreign('product_id')->references('product_id')->on('qc_products');
            $table->integer('allocationStaff_id')->unsigned(); // nhan vien giao
            $table->foreign('allocationStaff_id')->references('staff_id')->on('qc_staffs');
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

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WorkAllocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_work_allocation', function(Blueprint $table)
        {
            $table->increments('allocation_id');
            $table->dateTime('allocationDate'); // ngay nhan
            $table->tinyInteger('receiveStatus')->default(0); // trang thai nhan; 0 - duoc giao; 1 - tu nhan
            $table->dateTime('receiveDeadline'); // thoi gian nhan hoan thanh tu de xuat
            $table->tinyInteger('confirmStatus')->default(0); // trang thai xac nhan cong viec duoc giao; 0 - da xac nhan; 1 - chua xac nhan
            $table->dateTime('confirmDate'); // ngay xac nhan cong viec
            $table->text('noted');
            $table->tinyInteger('action')->default(1); // 0 - da ket thuc / 1 - dang hoat dong
            $table->dateTime('created_at');
            $table->integer('product_id')->unsigned()->nullable(); //lam san pham
            $table->foreign('product_id')->references('product_id')->on('qc_products');
            $table->integer('allocationStaff_id')->unsigned(); // nhan vien giao
            $table->foreign('allocationStaff_id')->references('staff_id')->on('qc_staffs');
            $table->integer('receiveStaff_id')->unsigned(); // nhan vien nhan
            $table->foreign('receiveStaff_id')->references('staff_id')->on('qc_staffs');
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

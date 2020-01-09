<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderAllocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_order_allocation', function(Blueprint $table)
        {
            $table->increments('allocation_id');
            $table->dateTime('allocationDate'); // ngay nhan
            $table->tinyInteger('receiveStatus')->default(0); // trang thai nhan; 0 - duoc giao; 1 - tu nhan
            $table->dateTime('receiveDeadline'); // thoi gian nhan hoan thanh duoc ban giao
            $table->text('noted');
            $table->tinyInteger('finishStatus')->default(0); // trang thai hoan thanh cong trinh cua NV nhan; 0 - ko; 1 - co
            $table->tinyInteger('finishDate')->nullable(); // ngay NV nhan bao hoan thanh;
            $table->tinyInteger('confirmStatus')->default(0); // he thong xac nhan ket thuc giao cong trinh; 0 - da xac nhan; 1 - chua xac nhan
            $table->tinyInteger('confirmFinish')->default(0); // he thong xac nhan cong trinh hoan thanh; 0 - ko; 1 - co
            $table->dateTime('confirmDate')->nullable(); // ngay xac nhan hoan thanh
            $table->tinyInteger('action')->default(1); // 0 - da ket thuc / 1 - dang hoat dong
            $table->dateTime('created_at');
            $table->integer('confirmStaff_id')->unsigned()->nullable(); // nhan vien xac nhan cong trinh hoanh thanh
            $table->foreign('confirmStaff_id')->references('staff_id')->on('qc_staffs');
            $table->integer('order_id')->unsigned(); //don hang ban giao
            $table->foreign('order_id')->references('order_id')->on('qc_orders');
            $table->integer('allocationStaff_id')->unsigned()->nullable(); // nhan vien giao id / null; null-> Giao khi nhan ??n hàng
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

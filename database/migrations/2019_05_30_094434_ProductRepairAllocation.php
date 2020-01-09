<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductRepairAllocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_product_repair_allocation', function(Blueprint $table)
        {
            $table->increments('allocation_id');
            $table->tinyInteger('position')->default(1); // vai tro lam; 0 - phu; 1 - làm chinh
            $table->tinyInteger('action')->default(1); // 0 - da ket thuc / 1 - dang hoat dong
            $table->dateTime('created_at');
            $table->integer('repair_id')->unsigned()->nullable(); //lam san pham
            $table->foreign('repair_id')->references('repair_id')->on('qc_product_repair');
            $table->integer('staff_id')->unsigned(); // nhan vien giao
            $table->foreign('staff_id')->references('staff_id')->on('qc_staffs');
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

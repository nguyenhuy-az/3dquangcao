<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WorkAllocationFinish extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_work_allocation_finish', function(Blueprint $table)
        {
            $table->increments('finish_id');
            $table->dateTime('finishDate');
            $table->tinyInteger('finishStatus')->default(1);// 0 - khong hoan thanh - 1 hoan thanh
            $table->tinyInteger('finishLevel')->default(0);// 0 - dung han; 1 - som; 2 tre
            $table->tinyInteger('finishReason')->default(0);// 0 - tu bao; 1 - he thong huy do nv lam khong dc ; 2 - nguoi phan viec sai
            $table->text('noted')->nullable(); // ghi chu
            $table->dateTime('created_at');
            $table->integer('allocation_id')->unsigned();
            $table->foreign('allocation_id')->references('allocation_id')->on('qc_work_allocation');
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

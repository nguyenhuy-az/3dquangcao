<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductRepairFinish extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_product_repair_finish', function(Blueprint $table)
        {
            $table->increments('finish_id');
            $table->dateTime('finishDate');
            $table->tinyInteger('finishStatus')->default(1);// 0 - khong hoan thanh - 1 hoan thanh
            $table->tinyInteger('finishLevel')->default(0);// 0 - dung han; 1 - som; 2 tre
            $table->text('noted')->nullable(); // ghi chu
            $table->dateTime('created_at');
            $table->integer('repair_id')->unsigned();
            $table->foreign('repair_id')->references('repair_id')->on('qc_product_repair');
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

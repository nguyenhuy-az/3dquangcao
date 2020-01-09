<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImportPay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_import_pay', function(Blueprint $table)
        {
            $table->increments('pay_id');
            $table->integer('money');
            $table->tinyInteger('confirmStatus')->default(0);
            $table->dateTime('created_at');
            $table->integer('import_id')->unsigned();
            $table->foreign('import_id')->references('import_id')->on('qc_import');
            $table->integer('staff_id')->unsigned();
            $table->foreign('staff_id')->references('staff_id')->on('qc_staffs');
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

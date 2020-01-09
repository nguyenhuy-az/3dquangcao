<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Import extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_import', function(Blueprint $table)
        {
            $table->increments('import_id');
            $table->dateTime('importDate');
            $table->dateTime('confirmDate',100);
            $table->tinyInteger('confirmStatus')->default(0);
            $table->tinyInteger('payStatus')->default(0);
            $table->tinyInteger('payConfirm')->default(0);
            $table->tinyInteger('exactlyStatus')->default(1);
            //$table->tinyInteger('action')->default(1);
            $table->dateTime('created_at');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('company_id')->on('qc_companies');
            $table->integer('confirmStaff_id')->unsigned();
            $table->foreign('confirmStaff_id')->references('staff_id')->on('qc_staffs');
            $table->integer('importStaff_id')->unsigned();
            $table->foreign('importStaff_id')->references('staff_id')->on('qc_staffs');
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

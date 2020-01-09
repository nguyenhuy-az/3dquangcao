<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Kpi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_kpi', function(Blueprint $table)
        {
            $table->increments('kpi_id');
            $table->integer('kpiLimit')->default(5000000);
            $table->tinyInteger('plusPercent')->default(1);
            $table->tinyInteger('minusPercent')->default(0);
            $table->text('description')->nullable();
            $table->tinyInteger('action')->default(0);
            $table->dateTime('created_at');
            $table->integer('department_id')->unsigned();
            $table->foreign('department_id')->references('department_id')->on('qc_departments')->onDelete('cascade');
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

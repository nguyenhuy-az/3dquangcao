<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Bonus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_bonus', function(Blueprint $table)
        {
            $table->increments('bonus_id');
            $table->integer('money');
            $table->dateTime('bonusDate');
            $table->text('note');
            $table->tinyInteger('applyStatus')->default(0);
            $table->tinyInteger('cancelStatus')->default(0);
            $table->dateTime('created_at');
            $table->integer('work_id')->unsigned();
            $table->foreign('work_id')->references('work_id')->on('qc_works')->onDelete('cascade');
            $table->integer('orderAllocation_id')->nullable()->unsigned();
            $table->foreign('orderAllocation_id')->references('allocation_id')->on('qc_staffs')->onDelete('cascade');
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

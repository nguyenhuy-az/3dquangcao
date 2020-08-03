<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MinusMoneyFeedback extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_minus_money_feedback', function(Blueprint $table)
        {
            $table->increments('feedback_id');
            $table->text('content');
            $table->string('image')->nullable();
            $table->tinyInteger('confirmStatus')->default(0);
            $table->dateTime('confirmDate')->nullable();
            $table->dateTime('created_at');
            $table->integer('minus_id')->unsigned();
            $table->foreign('minus_id')->references('minus_id')->on('qc_minus_money')->ondelete('cascade');
            $table->integer('confirmStaff_id')->unsigned();
            $table->foreign('confirmStaff_id')->references('staff_id')->on('qc_staffs')->ondelete('cascade');
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

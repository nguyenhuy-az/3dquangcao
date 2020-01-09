<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQcOffWork extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_off_works', function (Blueprint $table) {
            $table->increments('off_id');
            $table->tinyInteger('permission')->default(0);
            $table->text('note')->nullable();
            $table->dateTime('created_at');
            $table->integer('timekeeping_id')->unsigned();
            $table->foreign('timekeeping_id')->references('timekeeping_id')->on('qc_timekeeping')->onDelete('cascade');
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

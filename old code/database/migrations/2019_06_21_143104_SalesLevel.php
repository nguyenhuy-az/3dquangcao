<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SalesLevel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_sales_level', function(Blueprint $table)
        {
            $table->increments('level_id');
            $table->integer('levelPercent');
            $table->integer('levelMoney');
            $table->text('description')->nullable();
            $table->tinyInteger('action')->default(1);
            $table->dateTime('created_at');
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

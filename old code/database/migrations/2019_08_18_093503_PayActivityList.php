<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PayActivityList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_pay_activity_list', function(Blueprint $table)
        {
            $table->increments('payList_id');
            $table->string('name',100);
            $table->string('description',100);
            $table->tinyInteger('type')->default(1);
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

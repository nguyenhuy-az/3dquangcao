<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Customer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_customers', function(Blueprint $table)
        {
            $table->increments('customer_id');
            $table->string('nameCode',30)->unique();
            $table->string('name',30)->unique();
            $table->string('address',50)->nullable();
            $table->string('phone',30)->nullable();
            $table->string('email',250)->nullable();
            $table->string('zalo',30)->nullable();
            $table->date('birthDay')->nullable();
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

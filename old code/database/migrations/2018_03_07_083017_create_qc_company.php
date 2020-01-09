<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQcCompany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_companies', function(Blueprint $table)
        {
            $table->increments('company_id');
            $table->string('companyCode',10)->unique();
            $table->string('name',30)->unique();
            $table->string('address',50)->nullable();
            $table->string('phone',30)->nullable();
            $table->string('email',250)->nullable();
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

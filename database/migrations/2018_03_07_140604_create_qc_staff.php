<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQcStaff extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tf_staffs', function(Blueprint $table)
        {
            $table->increments('staff_id');
            $table->string('nameCode',20)->unique();
            $table->string('firstName',30);
            $table->string('lastName',20);
            $table->string('account',65)->unique();
            $table->string('password', 100);
            $table->string('identityCard',20);
            $table->string('birthday',100);
            $table->tinyInteger('gender');
            $table->string('image',100);
            $table->string('email',100);
            $table->string('address',100);
            $table->string('phone',30);
            $table->tinyInteger('level')->default(2);
            $table->dateTime('created_at');
            $table->integer('company_id')->unsigned()->nullable();
            $table->foreign('company_id')->references('company_id')->on('qc_companies');
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

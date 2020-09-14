<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DepartmentWork extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_department_work', function (Blueprint $table) {
            $table->increments('work_id');
            $table->string('name', 30)->unique();
            $table->text('description')->nullable();
            $table->dateTime('created_at');
            $table->integer('department_id')->unsigned();
            $table->foreign('department_id')->references('department_id')->on('qc_departments')->onDelete('cascade');
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

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BonusDepartment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_bonus_department', function (Blueprint $table) {
            $table->increments('bonus_id');
            $table->tinyInteger('percent');
            $table->text('description');
            $table->tinyInteger('applyStatus')->default(1);
            $table->tinyInteger('action')->default(1);
            $table->dateTime('created_at');
            $table->integer('rank_id')->unsigned();
            $table->foreign('rank_id')->references('rank_id')->on('qc_ranks')->onDelete('cascade');
            $table->integer('department_id')->unsigned();
            $table->foreign('department_id')->references('department_id')->on('qc_departments')->onDelete('cascade');;
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

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PunishContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_punish_content', function(Blueprint $table)
        {
            $table->increments('punish_id');
            $table->string('name',100)->unique();
            $table->integer('money');
            $table->text('note');
            $table->dateTime('created_at');
            $table->integer('type_id')->unsigned();
            $table->foreign('type_id')->references('type_id')->on('qc_punish_type');
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

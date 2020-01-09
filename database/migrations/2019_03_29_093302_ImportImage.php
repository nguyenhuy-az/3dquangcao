<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImportImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_import_image', function(Blueprint $table)
        {
            $table->increments('image_id');
            $table->string('name');
            $table->dateTime('created_at');
            $table->integer('import_id')->unsigned();
            $table->foreign('import_id')->references('import_id')->on('qc_import');
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

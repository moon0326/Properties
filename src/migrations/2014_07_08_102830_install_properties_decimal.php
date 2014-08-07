<?php

use Illuminate\Database\Migrations\Migration;

class InstallPropertiesDecimal extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties_decimal', function($table) {
            $table->increments('id');
            $table->integer('index_id');
            $table->string('name');
            $table->decimal('value', 14, 2);
            $table->engine = 'InnoDB';
            $table->index('index_id');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('properties_decimal');
    }

}

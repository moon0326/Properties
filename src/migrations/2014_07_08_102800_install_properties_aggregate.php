<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InstallPropertiesAggregate extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties_aggregate', function($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('pk');
            $table->integer('pk_value');
            $table->text('cached_properties')->nullable();
            $table->engine = 'InnoDB';
            $table->index('pk');
            $table->index('pk_value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('properties_aggregate');
    }

}

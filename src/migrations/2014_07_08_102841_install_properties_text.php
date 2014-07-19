<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InstallPropertiesText extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties_text', function($table) {
            $table->increments('id');
            $table->integer('index_id');
            $table->string('name');
            $table->text('value');
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
        Schema::drop('properties_text');
    }

}

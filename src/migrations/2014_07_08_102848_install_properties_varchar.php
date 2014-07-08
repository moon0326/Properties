<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InstallPropertiesVarchar extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('properties_varchar', function($table) {
			$table->increments('id');
			$table->integer('index_id');
			$table->string('key');
			$table->string('value');
			$table->engine = 'InnoDB';
			$table->index('index_id');
			$table->index('key');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('properties_varchar');
	}

}

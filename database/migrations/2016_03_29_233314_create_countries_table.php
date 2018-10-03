<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasTable('countries')) {
			Schema::create('countries', function(Blueprint $table)
			{
				$table->increments('id');
				$table->string('iso');
				$table->string('name');
				$table->string('nicename');
				$table->string('iso3');
				$table->integer('numcode');
				$table->integer('phonecode');
				$table->boolean('status');
				$table->timestamps();
			});
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('countries');
	}

}

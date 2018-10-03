<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VariableRatesMeta extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		if (!Schema::hasTable('variable_rates_meta')) {
			Schema::create('variable_rates_meta',function($table){
				$table->increments('id');
				$table->integer('meta_key');
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
		//
		Schema::drop('variable_rates_meta'); 
	}

}

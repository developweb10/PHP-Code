<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVariableRates extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		if (!Schema::hasTable('variable_rates')) {
			Schema::create('variable_rates',function($table){
				$table->increments('id');
				$table->integer('group_id');
				$table->float('price');
				$table->integer('hr_float');
				$table->integer('offer_duration');
				$table->text('start_time');
				$table->text('end_time');
				$table->timestamp('start_date');
				$table->timestamp('end_date');
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
		Schema::drop('variable_rates');
	}

}

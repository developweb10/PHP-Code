<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasTable('meters')) {
			Schema::create('meters', function(Blueprint $table)
			{
				$table->increments('id');
				$table->integer('lot_id');
				$table->integer('meter_id');
				$table->timestamp('expiry');
				$table->integer('user_id');
				
				$table->decimal('hours', 18, 2);
				$table->decimal('base_price',18,2);
				$table->decimal('hour_price',18,2);
				
				$table->boolean('status')->default(1);
				
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
		Schema::drop('meters');
	}

}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentItems extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasTable('payment_items')) {
			Schema::create('payment_items', function(Blueprint $table)
			{
				$table->increments('id');
				$table->integer('payment_id');
				$table->integer('user_id');
				$table->integer('lot_id');
				$table->integer('meter_id');
				$table->decimal('hours', 18, 2);
				$table->decimal('base_price',18,2);
				$table->decimal('hour_price',18,2);
				$table->decimal('total_price',18,2);
				
				$table->string('opted_phone_number');
				
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
		Schema::drop('payment_items');
	}

}

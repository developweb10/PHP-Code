<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasTable('settings')) {
			Schema::create('settings', function(Blueprint $table)
			{
				$table->increments('id');
				
				$table->decimal('sa_commission',18,2);
				$table->decimal('promo_code_discount_lo',18,2);
				$table->decimal('meter_price_lo',18,2);
				
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
		Schema::drop('settings');
	}

}

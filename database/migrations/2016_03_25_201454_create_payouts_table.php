<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayoutsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 *///
	public function up()
	{
		if (!Schema::hasTable('payouts')) {
			Schema::create('payouts', function(Blueprint $table)
			{
				$table->increments('id');
				
				$table->integer('user_id');
				$table->decimal('amount', 18, 2);
				$table->string('payment_email');
				$table->string('trans_id');
				$table->string('trans_status');
				$table->longText('trans_response');
				
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
		Schema::drop('payouts');
	}

}

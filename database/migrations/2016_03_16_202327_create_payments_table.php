<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasTable('payments')) {
			Schema::create('payments', function(Blueprint $table)
			{
				$table->increments('id');
				$table->integer('user_id');
				$table->integer('lot_id');
				$table->decimal('amount', 18, 2);
				$table->integer('trans_id');
				$table->string('trans_status');
				$table->longText('trans_response');
				$table->string('payment_type');
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
		Schema::drop('payments');
	}

}

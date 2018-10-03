<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferralCommissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 *///
	public function up()
	{
		if (!Schema::hasTable('referral_commissons')) {
			Schema::create('referral_commissons', function(Blueprint $table)
			{
				$table->increments('id');
				$table->integer('referral_id');
				$table->integer('payment_id');
				$table->double('commission',18,2);
				$table->double('commission_amount',18,2);
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
		Schema::drop('referral_commissons');
	}

}

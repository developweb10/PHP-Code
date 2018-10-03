<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferralsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 *///
	public function up()
	{
		if (!Schema::hasTable('referrals')) {
			Schema::create('referrals', function(Blueprint $table)
			{
				$table->increments('id');
				$table->integer('user_id');
				$table->integer('referred_by');
				$table->double('commission',18,2);
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
		Schema::drop('referrals');
	}

}

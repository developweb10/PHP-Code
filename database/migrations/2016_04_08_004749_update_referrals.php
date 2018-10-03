<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateReferrals extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasTable('referrals')) {
			Schema::table('referrals', function(Blueprint $table)
			{
				$table->string("referral_medium");
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
		Schema::table('referrals', function(Blueprint $table)
		{
			$table->dropColumn(["referral_medium"]);
		});
	}

}

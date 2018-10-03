<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmslogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasTable('sms_log')) {
			Schema::create('sms_log', function(Blueprint $table)
			{
				$table->increments('id');
				$table->string('to');
				$table->string('sms_body');
				$table->text('sms_status');
				$table->string('meter_id');
				$table->string('meter_owner');
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
		Schema::drop('sms_log');
	}

}

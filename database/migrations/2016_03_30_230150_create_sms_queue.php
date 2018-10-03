<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsQueue extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasTable('sms_queue')) {
			Schema::create('sms_queue', function(Blueprint $table)
			{
				$table->increments('id');
							$table->string('to');
							$table->string('sms_body');
							$table->timestamp('send_at');
							$table->integer('meter_id');
							$table->integer('meter_owner');
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
		Schema::drop('sms_queue');
	}

}

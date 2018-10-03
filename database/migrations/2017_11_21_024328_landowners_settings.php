<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LandownersSettings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		if (!Schema::hasTable('landowners_settings')) {  
			Schema::create('landowners_settings',function(Blueprint $table){
				$table->increments('id');
				$table->integer('user_id');
				$table->tinyInteger('variable_rates');
				$table->tinyInteger('sms_feature');
				$table->tinyInteger('email_feature');
				$table->string('recipient_mobile',255);
				$table->integer('recipient_email');
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
		//
		Schema::drop('landowners_settings');
	}

}

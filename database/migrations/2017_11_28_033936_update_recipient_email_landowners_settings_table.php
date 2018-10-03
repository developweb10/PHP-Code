<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRecipientEmailLandownersSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('landowners_settings', function (Blueprint $table) {
            $table->string('recipient_email')->nullabe()->change();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
		Schema::table('landowners_settings', function (Blueprint $table) {
            $table->integer('recipient_email')->change();
        });
	}

}

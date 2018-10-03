<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSignagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasTable('signages')) {
			Schema::create('signages', function(Blueprint $table)
			{
				$table->increments('id');
				$table->integer('user_id')->unsigned();
				$table->foreign('user_id')->references('id')->on('users');
				$table->string('name');
				$table->text('description');
				$table->string('img_url');
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
		Schema::drop('signages');
	}

}

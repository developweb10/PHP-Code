<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLotsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasTable('lots')) {
			Schema::create('lots', function(Blueprint $table)
			{
				$table->increments('id');
				$table->string('lot_name');
				$table->integer('user_id');
				
				$table->string('lot_address');
				$table->decimal('price', 18, 2);
				$table->timestamp('start_time');
				$table->timestamp('end_time');
				
				$table->boolean('status')->default(1);
				
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
		Schema::drop('lots');
	}

}

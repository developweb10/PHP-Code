<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasTable('users')) {
			Schema::create('users', function(Blueprint $table)
			{
				$table->increments('id');
				$table->integer('role_id')->nullable()->unsigned()->default(5);
				$table->string('name');
				$table->string('email')->unique();
				$table->string('password', 60);
				$table->rememberToken()->nullable();
				
				$table->string('street')->nullable();
				$table->string('city')->nullable();
				$table->string('country')->nullable();
				$table->string('email_payments')->nullable();
				
				$table->string('state')->nullable();
				$table->string('zip')->nullable();
				
				$table->timestamp('last_paid');
				$table->decimal('balance',18,2);
				
				$table->integer('referred_by');
				
				$table->boolean('deleted');
	
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
		Schema::drop('users');
	}

}

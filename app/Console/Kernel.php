<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'App\Console\Commands\Inspire',
		Commands\CustomCommand::class,   
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		
    	//$schedule->command('emails:send --force')->monthly();
		$schedule->command('command:name')->hourly();
		
		
		$schedule->call(function(){

		  $data = [];
	
		  Mail::raw('schedule job', function ($message) use ($data) {
			$message->from('example@mail.com', 'my name');
			$message->subject('test  schedule');
			$message->to('trannum.webtech@gmail.com')->cc('example@mail.com');
		  });
		})->hourly();
	}

}

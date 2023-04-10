<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
         Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
      //$schedule->command('work:day')->everyFiveMinutes(); // only this working 
		
  //$schedule->command('work:day')->daily();
	// $schedule->command('work:day')->cron('35 22 * * *');
             
  //  $schedule->command('work:day')
          //->daily()->at('18:50');
   // $schedule->command('work:day')->twiceDaily(4, 12);
               
               
    // $schedule->command('work:day');
    $schedule->command('work:day')->dailyAt('22:00');
           
   //$schedule->command('work:day')->twiceDaily(22, 23);
          
        // $schedule->command('work:day')
             // ->everyFiveMinutes();
		
		
    }
    
    
    

    
 
}

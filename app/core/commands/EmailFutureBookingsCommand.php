<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class EmailFutureBookingsCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'emailfuturebookings';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send emails with details of all future bookings.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        // get all future bookings, ordered by date asc        
        $data['future_bookings'] = \Booking::confirmed()
                ->where('date','>=', date('Y-m-d'))
                ->orderBy('date', 'asc')->get();
        
        \Mail::send('cron.email-future-bookings', $data, function($mail) {
            $mail->to(Setting::where('key', '=', 'staff_email')->first()->value); 
            $mail->subject("Future Bookings Report");
        });
        
        return true;
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
        return array();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
        return array();
    }

}

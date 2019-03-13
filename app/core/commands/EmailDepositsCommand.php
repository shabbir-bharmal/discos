<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class EmailDepositsCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'emaildeposits';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send emails with details of unpaid deposits.';

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
        $data = array();
        $date = new DateTime;
        $date->add(new DateInterval('P21D'));
        
        $data['unpaid_deposits'] = \Booking::confirmed()->where('deposit_paid','=', NULL)->orderBy('date', 'asc')->get();
        
        $data['unpaid_balances_coming_up'] = \Booking::confirmed()->where('balance_paid','=', NULL)->where('date','<=',$date->format('Y-m-d'))->orderBy('date', 'asc')->get();
        
        \Mail::send('cron.email-deposits', $data, function($mail) {
            $mail->to(Setting::where('key', '=', 'admin_email')->first()->value); 
            $mail->subject("Deposits & Balances Report");
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
		return array(
			array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
        return array();
		return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}

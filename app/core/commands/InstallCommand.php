<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class InstallCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'install';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

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
        # check we have db
            
            # run composer dumpautoload?
        
        Artisan::call('migrate:reset');
        
        
        
        Artisan::call('migrate');
        // todo: dynamically get all plugins
		Artisan::call('migrate', [
            '--bench'=>'tickbox/venues'
        ]);
		Artisan::call('migrate', [
            '--bench'=>'tickbox/events'
        ]);
        
        Artisan::call('db:seed');
        
        $this->info('Migrations all installed!');
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

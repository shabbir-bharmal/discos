<?php

#https://github.com/liebig/cron

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ScheduledEmailsCommand extends Command
{
    
    private $client_scheduled_emails;
    private $admin_scheduled_emails;
    private $today;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'emails:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send all scheduled emails.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        #$this->client_scheduled_emails = $client_scheduled_emails;
        #$this->admin_scheduled_emails = $admin_scheduled_emails;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        Log::info('Starting emails:send command');
        
        $mode = $this->argument('mode') == 'test' ? ScheduledEmails::TEST_MODE : ScheduledEmails::LIVE_MODE;
        
        if ($mode == ScheduledEmails::TEST_MODE) {
            $this->info('Running in test mode');
        }
        
        (new ScheduledEmails(new ClientScheduledEmails(), $mode))->run();
        (new ScheduledEmails(new AdminScheduledEmails(), $mode))->run();
        (new ScheduledEmails(new PartyEventEmails(), $mode))->run();
        (new ScheduledEmails(new PartyFinishedEventEmails(), $mode))->run();
        FollowUp::refresh();
        (new ScheduledEmails(new FollowUpEmails(), $mode))->run();
        (new ScheduledEmails(new OfferScheduledEmails(), $mode))->run();
        //(new ScheduledEmails(new OfferEmails(), $mode))->run();
        
        Log::info('emails:send finished.');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('mode', InputArgument::OPTIONAL, 'Test mode: either test or leave blank.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            #array(),
        );
    }
}

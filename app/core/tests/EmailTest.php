<?php

class EmailTest extends TestCase
{
    use TestEmailTrait;
    use TestBookingTrait;
    use TestClientTrait;


    /** @test */
    public function sendEmailWithCalculation()
    {
        $this->emails_sent = array();

        Event::listen('mailer.sending', function($mail){
            $this->emails_sent[] = $mail;
        });
        
        $this->addEmails();
        
        $emailTemplate = \EmailTemplate::find(1);
        $emailTemplate->html = 'Test: {{$booking["total_travel_cost"] + 120}}';
        $emailTemplate->save();
                
        $setting = new Setting();
        $setting->key = 'home_postcode';
        $setting->value = 'WC2N 5HS';
        $setting->notes = 'Postcode used in quotation calculations';
        $setting->save();
        
        $client = new \Client();
        $client->name = 'client';
        $client->save();

        $booking = new \Booking();
        $booking->date = '2014-12-12';
        $booking->date_booked = date('d-m-Y');
        $booking->venue_name = 'venue';
        $booking->venue_postcode = 'ta51th';
        $booking->package_id = 1;
        $package = \Package::find($booking->package_id);
        $booking->setup_equipment_time = $package->setup_time;

        $costs = \Costs::make($booking);  
        #$costs->setDebug(true);
        $booking = $costs->set_costs();

        $data['client'] = $client;
        $data['booking'] = $booking;
        
        Helpers\EmailHelper::sendQuotation('jo@jobult.co.uk', Helpers\EmailHelper::get_email_viewname($emailTemplate), $data, $client, $booking, $emailTemplate);

        $this->assertEquals('Test: '.($booking->total_travel_cost + 120), $this->emails_sent[0]->getBody());
    }
    
    /** test */
    public function saving_email_template_updates_file()
    {
        
    }
    
    /** @test */
    public function one_week_after_date_email_sent()
    {
        $this->emails_sent = array();
        
        Event::listen('mailer.sending', function($mail){
            $this->emails_sent[] = $mail;
        });
        
        $this->addEmails();
        
        $last_week = (new DateTime)->sub(new DateInterval('P7D'))->format('Y-m-d');
        
        $client_1 = $this->addClient();
        $client_1->email = 'correct@email.com';
        $client_1->save();
        $booking_1 = $this->addBooking($client_1, Package::find(1));
        $booking_1->date = $last_week;
        $booking_1->save();
        
        
        $emailTemplate = EmailTemplate::where('name', '=', 'GET TESTIMONIAL')->first();
        $emailTemplate->last_schedule = null;
        $emailTemplate->scheduled = 1;
        $emailTemplate->cc = 'jo@jo.jo';
        $emailTemplate->execution_hour = (new DateTime)->format('H');
        $emailTemplate->save();
        
        # run emails:send
        Artisan::call('emails:send');
        
        $emailTemplate = EmailTemplate::find($emailTemplate->id); //refresh
        
        #dd($this->emails_sent[0]->getFrom());
        
        // email was sent
        $this->assertCount(1, $this->emails_sent);
        
        // all email settings were used
        $this->assertArrayHasKey('correct@email.com', $this->emails_sent[0]->getTo());
        $this->assertArrayHasKey($emailTemplate->cc, $this->emails_sent[0]->getCc());
        $this->assertEquals('We Want Your Party Feedback! | venue | ***' . $booking_1->date . ' ' . $client_1->name . '***', $this->emails_sent[0]->getSubject());
        $this->assertArrayHasKey($emailTemplate->email_from, $this->emails_sent[0]->getFrom());
        $this->assertEquals($this->emails_sent[0]->getFrom()[$emailTemplate->email_from], $emailTemplate->name_from);
        
        // timestamp was updated
        $this->assertNotNull($emailTemplate->last_schedule);
    }
    
    /** @test */
    public function one_week_before_date_email_sent()
    {
        $this->emails_sent = array();
        
        Event::listen('mailer.sending', function($mail){
            $this->emails_sent[] = $mail;
        });
        
        $this->addEmails();
        
        $next_week = (new DateTime)->add(new DateInterval('P7D'))->format('Y-m-d');
        
        $client_1 = $this->addClient();
        $client_1->email = 'correct@email.com';
        $client_1->save();
        $booking_1 = $this->addBooking($client_1, Package::find(16));
        $booking_1->date = $next_week;
        $booking_1->save();
        
        $client_1 = $this->addClient();
        $client_1->email = 'correct@email.com';
        $client_1->save();
        $booking_2 = $this->addBooking($client_1, Package::find(1));
        $booking_2->date = (new DateTime)->add(new DateInterval('P8D'))->format('Y-m-d');
        $booking_2->save();
        
        
        $emailTemplate = EmailTemplate::where('name', '=', 'BOOKING REMINDER')->first();
        $emailTemplate->last_schedule = null;
        $emailTemplate->scheduled = 1;
        $emailTemplate->cc = 'jo@jo.jo';
        $emailTemplate->html = 'client name: {{$data->client->name}}';
        $emailTemplate->execution_hour = (new DateTime)->format('H');
        $emailTemplate->save();
        
        # run emails:send
        Artisan::call('emails:send');
        Artisan::call('emails:send'); // send again to show it only emails once
        
        $emailTemplate = EmailTemplate::find($emailTemplate->id); //refresh
        
        #dd($this->emails_sent[0]->getFrom());
        
        // email was sent
        $this->assertCount(1, $this->emails_sent);
        
        // all email settings were used
        $this->assertArrayHasKey('correct@email.com', $this->emails_sent[0]->getTo());
        $this->assertArrayHasKey($emailTemplate->cc, $this->emails_sent[0]->getCc());
        $this->assertEquals($emailTemplate->subject . ' | venue | ***' . $booking_1->date . ' ' . $client_1->name . '***', $this->emails_sent[0]->getSubject());
        $this->assertArrayHasKey($emailTemplate->email_from, $this->emails_sent[0]->getFrom());
        $this->assertEquals($this->emails_sent[0]->getFrom()[$emailTemplate->email_from], $emailTemplate->name_from);
        
        // body used data correctly
        $this->assertEquals('client name: client one', $this->emails_sent[0]->getBody());
        
        // timestamp was updated
        $this->assertNotNull($emailTemplate->last_schedule);        
    }
    
    /** @@test */
    public function at_the_start_of_the_date_email_sent()
    {
        $this->emails_sent = array();
        
        Event::listen('mailer.sending', function($mail){
            $this->emails_sent[] = $mail;
        });
        
        $this->addEmails();
        
        # add booking with today's date
        $client_1 = $this->addClient();
        $client_1->email = 'correct@email.com';
        $client_1->save();
        $booking = $this->addBooking($client_1, Package::find(1));
        $booking->start_time = (new DateTime)->format('H:i:s');
        $booking->save();
        
        
        $emailTemplate = EmailTemplate::where('name', '=', 'COOL KIDS PARTY FACEBOOK UPDATE')->first();
        $emailTemplate->last_schedule = null;
        $emailTemplate->scheduled = 1;
        $emailTemplate->cc = 'jo@jo.jo';
        $emailTemplate->html = 'client name: {{$data->client->name}}';
        $emailTemplate->save();
        
        # run send:emails
        Artisan::call('emails:send');
        Artisan::call('emails:send'); // will be sent twice if within the same hour
        
        # check event got fired & email sent
        $this->assertCount(2, $this->emails_sent);
        
        // all email settings were used
        $this->assertArrayHasKey($emailTemplate->recipient, $this->emails_sent[0]->getTo());
        $this->assertArrayHasKey('jo@jo.jo', $this->emails_sent[0]->getCc());
        $this->assertContains($emailTemplate->subject . ' | venue | ***' . $booking->date . ' ' . $client_1->name . '***', $this->emails_sent[0]->getSubject());
        $this->assertArrayHasKey($emailTemplate->email_from, $this->emails_sent[0]->getFrom());
        $this->assertEquals($this->emails_sent[0]->getFrom()[$emailTemplate->email_from], $emailTemplate->name_from);
        
        // body used data correctly
        $this->assertEquals('client name: client one', $this->emails_sent[0]->getBody());        
    }
    
    /** @test */
    public function admin_emails_are_sent_once_each_day()
    {
        $this->emails_sent = array();
        
        Event::listen('mailer.sending', function($mail){
            $this->emails_sent[] = $mail;
        });
        
        $this->addEmails();
        
        # add booking with today's date
        $client_1 = $this->addClient();
        $client_1->email = 'correct@email.com';
        $client_1->save();
        $booking = $this->addBooking($client_1, Package::find(1));
        $booking->save();
                
        
        $emailTemplate = EmailTemplate::where('name', '=', 'Unpaid deposits and balances')->first();
        $emailTemplate->last_schedule = null;
        $emailTemplate->scheduled = 1;
        $emailTemplate->cc = 'jo@jo.jo';
        $emailTemplate->html = 'first unpaid: {{$data["unpaid_deposits"][0]->client->name}}';
        $emailTemplate->execution_hour = (new DateTime)->format('H');
        $emailTemplate->save();
        
        
        # run send:emails
        Artisan::call('emails:send');
        Artisan::call('emails:send');
        
        # check event got fired & email sent
        $this->assertCount(1, $this->emails_sent);
        
        // all email settings were used
        $this->assertArrayHasKey($emailTemplate->recipient, $this->emails_sent[0]->getTo());
        $this->assertArrayHasKey('jo@jo.jo', $this->emails_sent[0]->getCc());
        $this->assertEquals($emailTemplate->subject, $this->emails_sent[0]->getSubject());
        $this->assertArrayHasKey($emailTemplate->email_from, $this->emails_sent[0]->getFrom());
        $this->assertEquals($this->emails_sent[0]->getFrom()[$emailTemplate->email_from], $emailTemplate->name_from);
        
        // body used data correctly
        $this->assertEquals('first unpaid: client one', $this->emails_sent[0]->getBody());   
    }
    
    /** @test */
    public function check_test_mode_doesnt_send_to_client()
    {
        $this->emails_sent = array();
        
        Event::listen('mailer.sending', function($mail){
            $this->emails_sent[] = $mail;
        });
        
        $this->addEmails();
        
        $next_week = (new DateTime)->add(new DateInterval('P7D'))->format('Y-m-d');
        
        $client_1 = $this->addClient();
        $client_1->email = 'correct@email.com';
        $client_1->save();
        $booking_1 = $this->addBooking($client_1, Package::find(14));
        $booking_1->date = $next_week;
        $booking_1->save();
        
        $client_1 = $this->addClient();
        $client_1->email = 'wrong@email.com';
        $client_1->save();
        $booking_2 = $this->addBooking($client_1, Package::find(14));
        $booking_2->date = (new DateTime)->add(new DateInterval('P8D'))->format('Y-m-d');
        $booking_2->save();
        
        
        $emailTemplate = EmailTemplate::where('name', '=', 'BOOKING REMINDER')->first();
        $emailTemplate->last_schedule = null;
        $emailTemplate->scheduled = 1;
        $emailTemplate->cc = 'jo@jo.jo';
        $emailTemplate->html = 'client name: {{$data->client->name}}';
        $emailTemplate->execution_hour = (new DateTime)->format('H');
        $emailTemplate->save();
        
        # run emails:send
        Artisan::call('emails:send', array('mode' => 'test'));
        
        $emailTemplate = EmailTemplate::find($emailTemplate->id); //refresh
        
        #dd($this->emails_sent[0]->getFrom());
        
        // email was sent
        $this->assertCount(1, $this->emails_sent);
        
        // all email settings were used
        $this->assertArrayNotHasKey('correct@email.com', $this->emails_sent[0]->getTo());
        $this->assertArrayHasKey(ScheduledEmails::TEST_EMAIL_RECIPIENT, $this->emails_sent[0]->getTo());
        $this->assertArrayNotHasKey($emailTemplate->cc, $this->emails_sent[0]->getCc());
        $this->assertArrayHasKey(ScheduledEmails::TEST_EMAIL_CC, $this->emails_sent[0]->getCc());
        $this->assertEquals($emailTemplate->subject . ' | venue | ***' . $booking_1->date . ' ' . $client_1->name . '***', $this->emails_sent[0]->getSubject());
        $this->assertArrayHasKey($emailTemplate->email_from, $this->emails_sent[0]->getFrom());
        $this->assertEquals($this->emails_sent[0]->getFrom()[$emailTemplate->email_from], $emailTemplate->name_from);
        
        // body used data correctly
        $this->assertEquals('client name: client one', $this->emails_sent[0]->getBody());
        
        // timestamp was updated
        $this->assertNotNull($emailTemplate->last_schedule);
        
    }

    /** @test */
    public function check_only_those_with_package_are_sent()
    {
        $this->emails_sent = array();
        
        Event::listen('mailer.sending', function($mail){
            $this->emails_sent[] = $mail;
        });
        
        $this->addEmails();
        
        $next_week = (new DateTime)->add(new DateInterval('P7D'))->format('Y-m-d');
        
        $client_1 = $this->addClient();
        $client_1->email = 'correct@email.com';
        $client_1->save();
        $booking_1 = $this->addBooking($client_1, Package::find(1));
        $booking_1->date = $next_week;
        $booking_1->save();
        
        $client_1 = $this->addClient();
        $client_1->email = 'wrong@email.com';
        $client_1->save();
        $booking_1 = $this->addBooking($client_1, Package::find(1)); // should not get sent
        $booking_1->date = (new DateTime)->add(new DateInterval('P8D'))->format('Y-m-d');
        $booking_1->save();
        
        $client_2 = $this->addClient();
        $client_2->email = 'correct@email.com';
        $client_2->save();
        $booking_2 = $this->addBooking($client_2, Package::find(13)); // should get sent
        $booking_2->date = (new DateTime)->add(new DateInterval('P7D'))->format('Y-m-d');
        $booking_2->save();
        
        
        $emailTemplate = EmailTemplate::where('name', '=', 'BOOKING REMINDER')->first();
        $emailTemplate->last_schedule = null;
        $emailTemplate->scheduled = 1;
        $emailTemplate->cc = 'jo@jo.jo';
        $emailTemplate->html = 'client name: {{$data->client->name}}';
        $emailTemplate->execution_hour = (new DateTime)->format('H');
        $emailTemplate->save();
        
        # run emails:send
        Artisan::call('emails:send');
        
        $emailTemplate = EmailTemplate::find($emailTemplate->id); //refresh
        
        #dd($this->emails_sent[0]->getFrom());
        
        // email was sent
        $this->assertCount(1, $this->emails_sent);
        
        // all email settings were used
        $this->assertArrayHasKey('correct@email.com', $this->emails_sent[0]->getTo());
        $this->assertArrayNotHasKey('wrong@email.com', $this->emails_sent[0]->getTo());
        $this->assertArrayHasKey($emailTemplate->cc, $this->emails_sent[0]->getCc());
        $this->assertEquals($emailTemplate->subject . ' | venue | ***' . $booking_2->date . ' ' . $client_1->name . '***', $this->emails_sent[0]->getSubject());
        $this->assertArrayHasKey($emailTemplate->email_from, $this->emails_sent[0]->getFrom());
        $this->assertEquals($this->emails_sent[0]->getFrom()[$emailTemplate->email_from], $emailTemplate->name_from);
        
        // body used data correctly
        $this->assertEquals('client name: client one', $this->emails_sent[0]->getBody());
        
        // timestamp was updated
        $this->assertNotNull($emailTemplate->last_schedule);        
    }
    
    /** @test */
    public function bug_unpaid_deposits_undefined()
    {
        $this->emails_sent = array();
        
        Event::listen('mailer.sending', function($mail){
            $this->emails_sent[] = $mail;
        });
        
        $this->addEmails();
        
        $next_week = (new DateTime)->add(new DateInterval('P7D'))->format('Y-m-d');
        
        $client_1 = $this->addClient();
        $client_1->email = 'correct@email.com';
        $client_1->save();
        $booking_1 = $this->addBooking($client_1, Package::find(1));
        $booking_1->date = $next_week;
        $booking_1->save();
        
        
        $emailTemplate = EmailTemplate::where('name', '=', 'Unpaid deposits and balances')->first();
        $emailTemplate->last_schedule = null;
        $emailTemplate->scheduled = 1;
        $emailTemplate->cc = 'jo@jo.jo';
        $emailTemplate->html = 'unpaid desposit 1 client name: {{$data["unpaid_deposits"][0]->client->name}}';
        $emailTemplate->execution_hour = (new DateTime)->format('H');
        $emailTemplate->save();
        
        # run emails:send
        Artisan::call('emails:send', array('mode' => 'test'));
        
        $emailTemplate = EmailTemplate::find($emailTemplate->id); //refresh
        
        #dd($this->emails_sent[0]->getFrom());
        
        // email was sent
        $this->assertCount(1, $this->emails_sent);
        
        // body used data correctly
        $this->assertEquals('unpaid desposit 1 client name: client one', $this->emails_sent[0]->getBody());
        
        // timestamp was updated
        $this->assertNotNull($emailTemplate->last_schedule);        
    }
    
    /** @test */
    public function timestamp_on_email_renders_correctly()
    {
        $this->emails_sent = array();
        
        Event::listen('mailer.sending', function($mail){
            $this->emails_sent[] = $mail;
        });
        
        $this->addEmails();
        
        $last_week = (new DateTime)->sub(new DateInterval('P7D'));
        
        $client_1 = $this->addClient();
        $client_1->email = 'correct@email.com';
        $client_1->save();
        $booking_1 = $this->addBooking($client_1, Package::find(1));
        $booking_1->date = $last_week->format('Y-m-d');
        $booking_1->save();
        
        
        $emailTemplate = EmailTemplate::where('name', '=', 'GET TESTIMONIAL')->first();
        $emailTemplate->last_schedule = null;
        $emailTemplate->scheduled = 1;
        $emailTemplate->cc = 'jo@jo.jo';
        $emailTemplate->html = 'timestamp: {{date("l jS \of F Y",strtotime($data->date))}}';
        $emailTemplate->execution_hour = (new DateTime)->format('H');
        $emailTemplate->save();
        
        # run emails:send
        Artisan::call('emails:send');
        
        $emailTemplate = EmailTemplate::find($emailTemplate->id); //refresh
        
        #dd($this->emails_sent[0]->getFrom());
        
        $this->assertEquals('timestamp: '.$last_week->format('l jS \of F Y'), $this->emails_sent[0]->getBody());      
    }
    
    /** @test */
    public function email_set_to_weekly_isnt_sent_daily()
    {
        $this->emails_sent = array();
        
        Event::listen('mailer.sending', function($mail){
            $this->emails_sent[] = $mail;
        });
        
        $this->addEmails();
        
        # add booking with today's date
        $client_1 = $this->addClient();
        $client_1->email = 'correct@email.com';
        $client_1->save();
        $booking = $this->addBooking($client_1, Package::find(1));
        $booking->save();
                
        
        $emailTemplate = EmailTemplate::where('name', '=', 'Unpaid deposits and balances')->first();
        $emailTemplate->last_schedule = null;
        $emailTemplate->scheduled = 1;
        $emailTemplate->cc = 'jo@jo.jo';
        $emailTemplate->regularity = EmailTemplate::REGULARITY_WEEKLY;
        $emailTemplate->day_of_week = strftime('%A', strtotime('tomorrow'));
        $emailTemplate->execution_hour = (new DateTime)->format('H');
        $emailTemplate->save();        
        
        # run send:emails
        Artisan::call('emails:send');        
        
        $this->assertCount(0, $this->emails_sent);
    }
    
    /** @test */
    public function email_set_to_weekly_sent_on_correct_day()
    {
        $this->emails_sent = array();
        
        Event::listen('mailer.sending', function($mail){
            $this->emails_sent[] = $mail;
        });
        
        $this->addEmails();
        
        # add booking with today's date
        $client_1 = $this->addClient();
        $client_1->email = 'correct@email.com';
        $client_1->save();
        $booking = $this->addBooking($client_1, Package::find(1));
        $booking->save();
                
        
        $emailTemplate = EmailTemplate::where('name', '=', 'Unpaid deposits and balances')->first();
        $emailTemplate->last_schedule = null;
        $emailTemplate->scheduled = 1;
        $emailTemplate->cc = 'jo@jo.jo';
        $emailTemplate->regularity = EmailTemplate::REGULARITY_WEEKLY;
        $emailTemplate->day_of_week = strftime('%A', time());
        $emailTemplate->execution_hour = (new DateTime)->format('H');
        $emailTemplate->save();        
        
        # run send:emails
        Artisan::call('emails:send');        
        
        $this->assertCount(1, $this->emails_sent);
    }
    /** @test */
    public function email_set_to_monthly_isnt_sent_daily()
    {
        $this->emails_sent = array();
        
        Event::listen('mailer.sending', function($mail){
            $this->emails_sent[] = $mail;
        });
        
        $this->addEmails();
        
        # add booking with today's date
        $client_1 = $this->addClient();
        $client_1->email = 'correct@email.com';
        $client_1->save();
        $booking = $this->addBooking($client_1, Package::find(1));
        $booking->save();
                
        
        $emailTemplate = EmailTemplate::where('name', '=', 'Unpaid deposits and balances')->first();
        $emailTemplate->last_schedule = null;
        $emailTemplate->scheduled = 1;
        $emailTemplate->cc = 'jo@jo.jo';
        $emailTemplate->regularity = EmailTemplate::REGULARITY_MONTHLY;
        $emailTemplate->day_of_month = (new DateTime)->format('j') + 1;
        $emailTemplate->execution_hour = (new DateTime)->format('H');
        $emailTemplate->save();        
        
        # run send:emails
        Artisan::call('emails:send');        
        
        $this->assertCount(0, $this->emails_sent);
    }
    
    /** @test */
    public function email_set_to_monthly_sent_on_correct_day()
    {
        $this->emails_sent = array();
        
        Event::listen('mailer.sending', function($mail){
            $this->emails_sent[] = $mail;
        });
        
        $this->addEmails();
        
        # add booking with today's date
        $client_1 = $this->addClient();
        $client_1->email = 'correct@email.com';
        $client_1->save();
        $booking = $this->addBooking($client_1, Package::find(1));
        $booking->save();
                
        
        $emailTemplate = EmailTemplate::where('name', '=', 'Unpaid deposits and balances')->first();
        $emailTemplate->last_schedule = null;
        $emailTemplate->scheduled = 1;
        $emailTemplate->cc = 'jo@jo.jo';
        $emailTemplate->regularity = EmailTemplate::REGULARITY_MONTHLY;
        $emailTemplate->day_of_month = (new DateTime)->format('j');
        $emailTemplate->execution_hour = (new DateTime)->format('H');
        $emailTemplate->save();        
        
        # run send:emails
        Artisan::call('emails:send');        
        
        $this->assertCount(1, $this->emails_sent);
    }
    
    /** @test */
    public function no_cc_doesnt_crash()
    {
        $this->emails_sent = array();
        
        Event::listen('mailer.sending', function($mail){
            $this->emails_sent[] = $mail;
        });
        
        $this->addEmails();
        
        # add booking with today's date
        $client_1 = $this->addClient();
        $client_1->email = 'correct@email.com';
        $client_1->save();
        $booking = $this->addBooking($client_1, Package::find(1));
        $booking->save();
                
        
        $emailTemplate = EmailTemplate::where('name', '=', 'Unpaid deposits and balances')->first();
        $emailTemplate->last_schedule = null;
        $emailTemplate->scheduled = 1;
        $emailTemplate->cc = '';
        $emailTemplate->regularity = EmailTemplate::REGULARITY_MONTHLY;
        $emailTemplate->day_of_month = (new DateTime)->format('j');
        $emailTemplate->execution_hour = (new DateTime)->format('H');
        $emailTemplate->save();        
        
        # run send:emails
        Artisan::call('emails:send');        
        
        $this->assertCount(1, $this->emails_sent);
    }
    
        
    /** test */
    public function future_bookings_time_calculations()
    {       
        
        $setting = new Setting();
        $setting->key = 'home_postcode';
        $setting->value = 'TA63TJ';
        $setting->notes = 'Postcode used in quotation calculations';
        $setting->save();
        
        $package = new Package();
        
        
        $this->emails_sent = array();
        
        Event::listen('mailer.sending', function($mail){
            $this->emails_sent[] = $mail;
        });
        
        $this->addEmails();
        
        # add booking with today's date
        $client_1 = $this->addClient();
        $client_1->email = 'correct@email.com';
        $client_1->save();
        $booking = $this->addBooking($client_1, Package::find(1));
        $booking->venue_postcode = 'BS229YX'; // 37 mins away
        $booking->save();                
        
        $emailTemplate = EmailTemplate::where('name', '=', 'Unpaid deposits and balances')->first();
        $emailTemplate->last_schedule = null;
        $emailTemplate->scheduled = 1;
        $emailTemplate->cc = 'jo@jo.jo';
        $emailTemplate->html = 'calculation: {{""; $start_timestamp = $data["unpaid_deposits"][0]->start_timestamp;'
                . '$start_timestamp -= (60 * $data["unpaid_deposits"][0]->package->setup_time); '
                . '$start_timestamp -= (60 * $data["unpaid_deposits"][0]->travel_time); '
                . 'echo strftime("%H:%M", $start_timestamp)}}';
        $emailTemplate->execution_hour = (new DateTime)->format('H');
        $emailTemplate->save();
        
        
        # run send:emails
        Artisan::call('emails:send');
        
        // body used data correctly
        $this->assertEquals('calculation: 10:38', $this->emails_sent[0]->getBody());  #  12:00 - 45 - 37)
    }
    
    /** @test */
    public function admin_emails_show_only_bookings_in_the_next_21_days()
    {
        $this->emails_sent = array();
        
        Event::listen('mailer.sending', function($mail){
            $this->emails_sent[] = $mail;
        });
        
        $this->addEmails();
        
        $client_1 = $this->addClient();
        $client_1->email = 'incorrect@email.com';
        $client_1->save();
        $booking = $this->addBooking($client_1, Package::find(1));
        $booking->date = (new DateTime)->add(new DateInterval('P22D'))->format('Y-m-d');
        $booking->save();
                
        
        $emailTemplate = EmailTemplate::where('name', '=', 'Unpaid deposits and balances')->first();
        $emailTemplate->last_schedule = null;
        $emailTemplate->scheduled = 1;
        $emailTemplate->cc = 'jo@jo.jo';
        $emailTemplate->html = 'count of unpaid deposits/balances: {{$data["unpaid_deposits"]->count()}}/{{$data["unpaid_balances_coming_up"]->count()}}';
        $emailTemplate->execution_hour = (new DateTime)->format('H');
        $emailTemplate->save();
        
        
        # run send:emails
        Artisan::call('emails:send');
        
        # check event got fired & email sent
        $this->assertCount(1, $this->emails_sent); // email will still be sent
        $this->assertEquals('count of unpaid deposits/balances: 0/0', $this->emails_sent[0]->getBody());
    }

    /** @test */
    public function event_emails_sent_to_correct_recipient()
    {
        $this->emails_sent = array();

        Event::listen('mailer.sending', function($mail){
            $this->emails_sent[] = $mail;
        });

        $this->addEmails();

        # add booking with today's date
        $client_1 = $this->addClient();
        $client_1->email = 'client@email.com';
        $client_1->save();
        $booking = $this->addBooking($client_1, Package::find(1));
        $booking->start_time = (new DateTime)->format('H:i:s');
        $booking->save();


        $emailTemplate = EmailTemplate::where('name', '=', 'COOL KIDS PARTY FACEBOOK UPDATE')->first();
        $emailTemplate->last_schedule = null;
        $emailTemplate->scheduled = 1;
        $emailTemplate->cc = 'jo@jo.jo';
        $emailTemplate->recipient = EmailTemplate::RECIPIENT_CLIENT;
        $emailTemplate->html = 'client name: {{$data->client->name}}';
        $emailTemplate->type = EmailTemplate::TYPE_EVENT_PARTY;
        $emailTemplate->filter = 'event';
        $emailTemplate->save();

        # run send:emails
        Artisan::call('emails:send');

        # check event got fired & email sent
        $this->assertCount(1, $this->emails_sent);

        // all email settings were used
        $this->assertArrayHasKey('client@email.com', $this->emails_sent[0]->getTo());
    }

    /** @group finish_party */
    public function test_during_party_wont_get_email()
    {
        $this->emails_sent = array();

        Event::listen('mailer.sending', function($mail){
            $this->emails_sent[] = $mail;
        });

        $this->addEmails();

        # add booking with today's date
        $client_1 = $this->addClient();
        $client_1->email = 'client@email.com';
        $client_1->save();
        $booking = $this->addBooking($client_1, Package::find(1));
        $booking->date = date('Y-m-d');
        $booking->finish_time = \Carbon\Carbon::now()->subHour()->format('H:i:s');
        $booking->save();


        $emailTemplate = EmailTemplate::where('name', 'Party just finished')->first();
        $emailTemplate->last_schedule = null;
        $emailTemplate->scheduled = 1;
        $emailTemplate->cc = 'jo@jo.jo';
        $emailTemplate->recipient = EmailTemplate::RECIPIENT_CLIENT;
        $emailTemplate->html = 'client name: {{$data->client->name}}';
        $emailTemplate->type = EmailTemplate::TYPE_EVENT_PARTY_FINISHED;
        $emailTemplate->filter = 'event';
        $emailTemplate->save();

        # run send:emails
        Artisan::call('emails:send');

        # check event got fired & email sent
        $this->assertCount(0, $this->emails_sent);
    }

    /** @group finish_party */
    public function test_party_finished_only_gets_email_that_hour()
    {
        $this->emails_sent = array();

        Event::listen('mailer.sending', function($mail){
            $this->emails_sent[] = $mail;
        });

        $this->addEmails();

        # add booking with today's date
        $client_1 = $this->addClient();
        $client_1->email = 'client@email.com';
        $client_1->save();
        $booking = $this->addBooking($client_1, Package::find(1));
        $booking->date = date('Y-m-d');
        $booking->finish_time = \Carbon\Carbon::now()->addHour()->format('H:i:s');
        $booking->save();


        $emailTemplate = EmailTemplate::where('name', 'Party just finished')->first();
        $emailTemplate->last_schedule = null;
        $emailTemplate->scheduled = 1;
        $emailTemplate->cc = 'jo@jo.jo';
        $emailTemplate->recipient = EmailTemplate::RECIPIENT_CLIENT;
        $emailTemplate->html = 'client name: {{$data->client->name}}';
        $emailTemplate->type = EmailTemplate::TYPE_EVENT_PARTY_FINISHED;
        $emailTemplate->filter = 'event';
        $emailTemplate->save();

        # run send:emails
        Artisan::call('emails:send');

        $this->assertCount(0, $this->emails_sent);
    }

    public function test_party_crossing_midnight_finished_gets_email()
    {
        # add booking with today's date
        $client_1 = $this->addClient();
        $client_1->email = 'client@email.com';
        $client_1->save();
        $booking = $this->addBooking($client_1, Package::find(1));
        $booking->date = \Carbon\Carbon::today()->format('Y-m-d'); // started today
        $booking->finish_time = '23:00:00'; // finished today
        $booking->save();

        $this->addEmails();

        $emailTemplate = EmailTemplate::where('name', 'Party just finished')->first();
        $emailTemplate->last_schedule = null;
        $emailTemplate->scheduled = 1;
        $emailTemplate->cc = 'jo@jo.jo';
        $emailTemplate->recipient = EmailTemplate::RECIPIENT_CLIENT;
        $emailTemplate->html = 'client name: {{$data->client->name}}';
        $emailTemplate->type = EmailTemplate::TYPE_EVENT_PARTY_FINISHED;
        $emailTemplate->filter = 'event';
        $emailTemplate->save();


        $event = new PartyFinishedEventEmails();

        $data = $event->setTime(\Carbon\Carbon::today()->setTime(23,30,0))->get_data_for_emails($emailTemplate);

        $this->assertCount(1, $data);

        $data = $event->setTime(\Carbon\Carbon::tomorrow()->setTime(0,30,0))->get_data_for_emails($emailTemplate);

        $this->assertCount(0, $data);
    }


    public function test_party_before_midnight_finished_gets_email()
    {
        # add booking with today's date
        $client_1 = $this->addClient();
        $client_1->email = 'client@email.com';
        $client_1->save();
        $booking = $this->addBooking($client_1, Package::find(1));
        $booking->date = \Carbon\Carbon::today()->format('Y-m-d'); // started today
        $booking->finish_time = '13:00:00'; // finished today
        $booking->save();

        $this->addEmails();

        $emailTemplate = EmailTemplate::where('name', 'Party just finished')->first();
        $emailTemplate->last_schedule = null;
        $emailTemplate->scheduled = 1;
        $emailTemplate->cc = 'jo@jo.jo';
        $emailTemplate->recipient = EmailTemplate::RECIPIENT_CLIENT;
        $emailTemplate->html = 'client name: {{$data->client->name}}';
        $emailTemplate->type = EmailTemplate::TYPE_EVENT_PARTY_FINISHED;
        $emailTemplate->filter = 'event';
        $emailTemplate->save();


        $event = new PartyFinishedEventEmails();

        $data = $event->setTime(\Carbon\Carbon::today()->setTime(14,30,0))->get_data_for_emails($emailTemplate);

        $this->assertCount(0, $data);
        $data = $event->setTime(\Carbon\Carbon::today()->setTime(15,0,0))->get_data_for_emails($emailTemplate);

        $this->assertCount(0, $data);
        $data = $event->setTime(\Carbon\Carbon::today()->setTime(13,20,0))->get_data_for_emails($emailTemplate);

        $this->assertCount(1, $data);
    }

    public function test_party_after_midnight_finished_gets_email()
    {
        # add booking with today's date
        $client_1 = $this->addClient();
        $client_1->email = 'client@email.com';
        $client_1->save();
        $booking = $this->addBooking($client_1, Package::find(1));
        $booking->date = \Carbon\Carbon::yesterday()->format('Y-m-d'); // started yesterday
        $booking->finish_time = '01:00:00'; // finished this morning
        $booking->save();

        $this->addEmails();

        $emailTemplate = EmailTemplate::where('name', 'Party just finished')->first();
        $emailTemplate->last_schedule = null;
        $emailTemplate->scheduled = 1;
        $emailTemplate->cc = 'jo@jo.jo';
        $emailTemplate->recipient = EmailTemplate::RECIPIENT_CLIENT;
        $emailTemplate->html = 'client name: {{$data->client->name}}';
        $emailTemplate->type = EmailTemplate::TYPE_EVENT_PARTY_FINISHED;
        $emailTemplate->filter = 'event';
        $emailTemplate->save();


        $event = new PartyFinishedEventEmails();

        // run at 2am this morning
        $data = $event->setTime(\Carbon\Carbon::today()->setTime(1,50,0))->get_data_for_emails($emailTemplate);

        $this->assertCount(1, $data);
    }

    /** @group finish_party */
    public function test_party_finished_gets_email()
    {
        $this->emails_sent = array();

        Event::listen('mailer.sending', function($mail){
            $this->emails_sent[] = $mail;
        });

        $this->addEmails();

        # add booking with today's date
        $client_1 = $this->addClient();
        $client_1->email = 'client@email.com';
        $client_1->save();
        $booking = $this->addBooking($client_1, Package::find(1));
        $booking->date = date('Y-m-d');
        $booking->finish_time = \Carbon\Carbon::now()->format('H:i:s');
        $booking->save();


        $emailTemplate = EmailTemplate::where('name', 'Party just finished')->first();
        $emailTemplate->last_schedule = null;
        $emailTemplate->scheduled = 1;
        $emailTemplate->cc = 'jo@jo.jo';
        $emailTemplate->recipient = EmailTemplate::RECIPIENT_CLIENT;
        $emailTemplate->html = 'client name: {{$data->client->name}}';
        $emailTemplate->type = EmailTemplate::TYPE_EVENT_PARTY_FINISHED;
        $emailTemplate->filter = 'event';
        $emailTemplate->save();

        # run send:emails
        Artisan::call('emails:send');

        # check event got fired & email sent
        $this->assertCount(1, $this->emails_sent);

        // all email settings were used
        $this->assertArrayHasKey('client@email.com', $this->emails_sent[0]->getTo());
    }

    /** @test finish_party */
    public function emails_arent_sent_to_pending_clients()
    {
        $this->emails_sent = array();

        Event::listen('mailer.sending', function($mail){
            $this->emails_sent[] = $mail;
        });

        $this->addEmails();

        # add booking with today's date
        $client_1 = $this->addClient();
        $client_1->email = 'client@email.com';
        $client_1->save();
        $booking = $this->addBooking($client_1, Package::find(1));
        $booking->status = Booking::STATUS_PENDING;
        $booking->date = date('Y-m-d');
        $booking->finish_time = \Carbon\Carbon::now()->format('H:i:s');
        $booking->save();


        $emailTemplate = EmailTemplate::where('name', 'Party just finished')->first();
        $emailTemplate->last_schedule = null;
        $emailTemplate->scheduled = 1;
        $emailTemplate->cc = 'jo@jo.jo';
        $emailTemplate->recipient = EmailTemplate::RECIPIENT_CLIENT;
        $emailTemplate->html = 'client name: {{$data->client->name}}';
        $emailTemplate->type = EmailTemplate::TYPE_EVENT_PARTY_FINISHED;
        $emailTemplate->filter = 'event';
        $emailTemplate->save();

        # run send:emails
        Artisan::call('emails:send');

        # check event got fired & email sent
        $this->assertCount(0, $this->emails_sent);
    }
}

?>

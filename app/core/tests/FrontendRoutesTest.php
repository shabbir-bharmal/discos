<?php

class FrontendRoutesTest extends TestCase {

    use TestClientTrait;
    use TestBookingTrait;
    use TestEmailTrait;

    //*********************************
    // QUOTE
    //*********************************

    /** @test */
    public function fields_are_validated()
    {
        $input = [
            'date' => 'invalid',
            'start_time' => '09:00',
            // missing finish_time
            'venue_name' => '',
            'venue_postcode' => 'ta51',
            'package_name' => "Children's Party Disco",
            // missing name
            'email' => 'jo@jo.jo'
        ];

        $response = $this->call('POST', '', $input, [], ['HTTP_REFERER' => 'http://new-disco']);

        $this->assertSessionHasErrors('date');
        $this->assertSessionHasErrors('finish_time');
        $this->assertSessionHasErrors('venue_name');
        $this->assertSessionHasErrors('venue_postcode');
        $this->assertSessionHasErrors('name');
    }

    /** @test */
    public function fields_pass_validation()
    {
        Setting::add('home_postcode', 'ta51th');
        $this->addEmails();

        $input = [
            'date' => Carbon\Carbon::today()->addDay()->format('d-m-Y'),
            'start_time' => '09:00',
            'finish_time' => '12:00',
            'venue_name' => 'church hall',
            'venue_postcode' => 'ta51th',
            'package_name' => "Children's Party Disco",
            'name' => 'Joe Dunn',
            'email' => 'jo@jo.jo',
            'telephone' => '0123456789',
            'mobile' => '078978463',
            'heard_about' => '',
            'ref_no' => ''
        ];

        $response = $this->call('POST', '', $input, [], ['HTTP_REFERER' => 'http://new-disco']);

        $errors = Session::get('errors');

        $this->assertNull($errors);
        $this->assertResponseOk();
    }

    /** @test */
    public function unavailable_date_flagged()
    {
        $client = $this->addClient();

        $booking = $this->add_booking(Carbon\Carbon::today()->addDays(10)->format('d-m-Y'), '09:00:00', '11:00:00', Booking::STATUS_BOOKING);

        $input = [
            'date' => Carbon\Carbon::today()->addDays(10)->format('d-m-Y'),
            'start_time' => '09:00',
            'finish_time' => '12:00',
            'venue_name' => 'church hall',
            'venue_postcode' => 'ta51th',
            'package_name' => "Children's Party Disco",
            'name' => 'Joe Dunn',
            'email' => 'jo@jo.jo',
            'telephone' => '0123456789',
            'mobile' => '078978463',
            'heard_about' => '',
            'ref_no' => ''
        ];

        $response = $this->call('POST', '', $input, [], ['HTTP_REFERER' => 'http://new-disco']);

        $this->assertRedirectedTo('http://new-disco');

        $this->assertSessionHasErrors('availability');
    }

    /** @test */
    public function available_date_passes()
    {
        Setting::add('home_postcode', 'ta51th');
        $this->addEmails();

        $client = $this->addClient();

        $booking = $this->add_booking(Carbon\Carbon::today()->addDays(11)->format('d-m-Y'), '09:00:00', '11:00:00', Booking::STATUS_BOOKING);

        $input = [
            'date' => Carbon\Carbon::today()->addDays(10)->format('d-m-Y'),
            'start_time' => '09:00',
            'finish_time' => '12:00',
            'venue_name' => 'church hall',
            'venue_postcode' => 'ta51th',
            'package_name' => "Children's Party Disco",
            'name' => 'Joe Dunn',
            'email' => 'jo@jo.jo',
            'telephone' => '0123456789',
            'mobile' => '078978463',
            'heard_about' => '',
            'ref_no' => ''
        ];

        $response = $this->call('POST', '', $input, [], ['HTTP_REFERER' => 'http://new-disco']);

        $errors = Session::get('errors');

        $this->assertNull($errors);
        $this->assertResponseOk();
    }

    /** @test */
    public function client_and_pending_booking_stored()
    {
        Setting::add('home_postcode', 'ta51th');
        $this->addEmails();

        $input = [
            'date' => Carbon\Carbon::today()->addDays(10)->format('d-m-Y'),
            'start_time' => '09:00',
            'finish_time' => '12:00',
            'venue_name' => 'church hall',
            'venue_postcode' => 'ta51th',
            'package_name' => "Children's Party Disco",
            'name' => 'Joe Dunn',
            'email' => 'jo@jo.jo',
            'telephone' => '0123456789',
            'mobile' => '078978463',
            'heard_about' => '',
            'ref_no' => ''
        ];

        $response = $this->call('POST', '', $input, [], ['HTTP_REFERER' => 'http://new-disco']);

        # get booking from response data
        $this->assertViewHas('booking');
        $this->assertNotNull($response->original['booking']);
        $this->assertEquals('12:00:00', $response->original['booking']->finish_time);
        $this->assertEquals(Booking::STATUS_PENDING, $response->original['booking']->status);
        $this->assertEquals('Joe Dunn', Client::findOrFail($response->original['booking']->client_id)->name);
    }

    /** @test */
    public function emails_sent()
    {
        Setting::add('home_postcode', 'ta51th');
        $this->addEmails();

        $this->emails_sent = array();

        Event::listen('mailer.sending', function($mail){
            $this->emails_sent[] = $mail;
        });

        $this->addEmails();

        $input = [
            'date' => Carbon\Carbon::today()->addDays(10)->format('d-m-Y'),
            'start_time' => '09:00',
            'finish_time' => '12:00',
            'venue_name' => 'church hall',
            'venue_postcode' => 'ta51th',
            'package_name' => "Children's Party Disco",
            'name' => 'Joe Dunn',
            'email' => 'jo@jo.jo',
            'telephone' => '0123456789',
            'mobile' => '078978463',
            'heard_about' => '',
            'ref_no' => ''
        ];

        $response = $this->call('POST', '', $input, [], ['HTTP_REFERER' => 'http://new-disco']);

        $this->assertCount(1, $this->emails_sent);
        $this->assertArrayHasKey('jo@jo.jo', $this->emails_sent[0]->getTo());
        $this->assertArrayHasKey('cc@email.com', $this->emails_sent[0]->getCc());
    }


    //*********************************
    // TOKEN
    //*********************************


    /** @test */
    public function invalid_token_redirects()
    {

        Setting::add('home_postcode', 'ta51th');
        $this->addEmails();

        $input = [
            'date' => Carbon\Carbon::today()->addDays(10)->format('d-m-Y'),
            'start_time' => '09:00',
            'finish_time' => '12:00',
            'venue_name' => 'church hall',
            'venue_postcode' => 'ta51th',
            'package_name' => "Children's Party Disco",
            'name' => 'Joe Dunn',
            'email' => 'jo@jo.jo',
            'telephone' => '0123456789',
            'mobile' => '078978463',
            'heard_about' => '',
            'ref_no' => ''
        ];

        $response = $this->call('POST', '', $input, [], ['HTTP_REFERER' => 'http://new-disco']);

        $response = $this->call('GET', 'booking/invalid');

        $this->assertRedirectedTo('http://localhost');
    }

    /** @test */
    public function unavailable_date_on_token_retrieve_flagged()
    {

        Setting::add('home_postcode', 'ta51th');
        $this->addEmails();

        $input = [
            'date' => Carbon\Carbon::today()->addDays(10)->format('d-m-Y'),
            'start_time' => '09:00',
            'finish_time' => '12:00',
            'venue_name' => 'church hall',
            'venue_postcode' => 'ta51th',
            'package_name' => "Children's Party Disco",
            'name' => 'Joe Dunn',
            'email' => 'jo@jo.jo',
            'telephone' => '0123456789',
            'mobile' => '078978463',
            'heard_about' => '',
            'ref_no' => ''
        ];

        $response = $this->call('POST', '', $input, [], ['HTTP_REFERER' => 'http://new-disco']);

        $pending_booking = $response->original['booking'];

        // book something at the same time so no longer available
        $booking = $this->add_booking(Carbon\Carbon::today()->addDays(10)->format('d-m-Y'), '09:00:00', '11:00:00', Booking::STATUS_BOOKING);

        $response = $this->call('GET', 'booking/' . $pending_booking->email_token);

        $this->assertNull(Session::get('booking_id', null));
        $this->assertSessionHasErrors('availability');
    }

    /** @test */
    public function no_session_redirects_to_validate_yourself()
    {
        Setting::add('home_postcode', 'ta51th');
        $this->addEmails();

        $input = [
            'date' => Carbon\Carbon::today()->addDays(10)->format('d-m-Y'),
            'start_time' => '09:00',
            'finish_time' => '12:00',
            'venue_name' => 'church hall',
            'venue_postcode' => 'ta51th',
            'package_name' => "Children's Party Disco",
            'name' => 'Joe Dunn',
            'email' => 'jo@jo.jo',
            'telephone' => '0123456789',
            'mobile' => '078978463',
            'heard_about' => '',
            'ref_no' => ''
        ];

        $response = $this->call('POST', '', $input, [], ['HTTP_REFERER' => 'http://new-disco']);

        $pending_booking = $response->original['booking'];

        unset($_SESSION['booking_id']);

        $response = $this->call('GET', 'booking/' . $pending_booking->email_token);

        $this->assertContains('Please verify yourself', $response->getContent());
    }


    //*********************************
    // CONFIRM
    //*********************************

    /** @test */
    public function unavailable_date_on_token_confirm_flagged()
    {
        $this->flushSession();

        Setting::add('home_postcode', 'ta51th');
        $this->addEmails();

        $input = [
            'date' => Carbon\Carbon::today()->addDays(10)->format('d-m-Y'),
            'start_time' => '09:00',
            'finish_time' => '12:00',
            'venue_name' => 'church hall',
            'venue_postcode' => 'ta51th',
            'package_name' => "Children's Party Disco",
            'name' => 'Joe Dunn',
            'email' => 'jo@jo.jo',
            'telephone' => '0123456789',
            'mobile' => '078978463',
            'heard_about' => '',
            'ref_no' => ''
        ];

        $response = $this->call('POST', '', $input, [], ['HTTP_REFERER' => 'http://new-disco']);

        $pending_booking = $response->original['booking'];

        // book something at the same time so no longer available
        $booking = $this->add_booking(Carbon\Carbon::today()->addDays(10)->format('d-m-Y'), '09:00:00', '11:00:00', Booking::STATUS_BOOKING);

        $confirm_input = [
            'venue_name' => 'church hall',
            'venue_address1' => 'add 1',
            'venue_postcode' => 'ta51th',
            'event_occasion' => 'wedding',
            'name' => 'Joe Dunn',
            'address1'=>'add 1',
            'address2'=>'add 2',
            'postcode'=>'tr5 4rt'
        ];

        $response = $this->call('POST', 'booking', $confirm_input, [], ['HTTP_REFERER' => 'http://new-disco/booking/' . $pending_booking->email_token]);

        $this->assertNull(Session::get('booking_id', null));
        $this->assertSessionHasErrors('availability');
    }

    /** @test */
    public function date_and_status_updated()
    {
        $this->flushSession();

        Setting::add('home_postcode', 'ta51th');
        $this->addEmails();

        $input = [
            'date' => Carbon\Carbon::today()->addDays(10)->format('d-m-Y'),
            'start_time' => '09:00',
            'finish_time' => '12:00',
            'venue_name' => 'church hall',
            'venue_postcode' => 'ta51th',
            'package_name' => "Children's Party Disco",
            'name' => 'Joe Dunn',
            'email' => 'jo@jo.jo',
            'telephone' => '0123456789',
            'mobile' => '078978463',
            'heard_about' => '',
            'ref_no' => ''
        ];

        $response = $this->call('POST', '', $input, [], ['HTTP_REFERER' => 'http://new-disco']);

        $pending_booking = $response->original['booking'];

        $confirm_input = [
            'venue_name' => 'church hall',
            'venue_address1' => 'add 1',
            'venue_address2' => 'add 2',
            'venue_address3' => 'add 2',
            'venue_postcode' => 'postcode',
            'event_occasion' => 'wedding',
            'bride_firstname' => 'first',
            'groom_firstname' => 'last',
            'groom_surname' => 'surnmae',
            'name' => 'Jane Dunn',
            'address1'=>'add 1',
            'address2'=>'add 2',
            'address3'=>'add 3',
            'postcode'=>'tr5 4rt'
        ];

        $response = $this->call('POST', 'booking', $confirm_input, [], ['HTTP_REFERER' => 'http://new-disco/booking/' . $pending_booking->email_token]);

        $this->assertEquals(Booking::STATUS_BOOKING, Booking::find($pending_booking->id)->status);
        $this->assertEquals(date('d-m-Y'), Booking::find($pending_booking->id)->date_booked);
        $this->assertEquals('postcode', Booking::find($pending_booking->id)->venue_postcode);
        $this->assertEquals('first', Booking::find($pending_booking->id)->bride_firstname);
        $this->assertEquals('Jane Dunn', Client::find($pending_booking->client_id)->name);
    }
}
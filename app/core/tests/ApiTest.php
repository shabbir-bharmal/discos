<?php

class ApiTest extends TestCase
{
    use TestClientTrait, TestBookingTrait, TestEmailTrait;

    /** can't test this as filters are disabled in test mode */
    public function api_authentication_works()
    {
        $response = $this->call('POST', 'api/bookings');
        $this->assertResponseStatus(403);
    }

    /** test - slow */
    public function get_block_quotes_validates_date_invalid_date()
    {
        $response = $this->call('GET', 'api/bookings/block-quotes/invalid/ta51df');

        $reply = json_decode($response->getContent(), false);

        $this->assertEquals(400, $reply->http);
        $this->assertResponseStatus(400);
        $this->assertObjectHasAttribute('date', $reply->data);
        $this->assertEquals('The date is not a valid date.', $reply->data->date[0]);
    }

    /** test - slow  */
    public function get_block_quotes_supplies_four_week_range()
    {
        $date_to_check = Carbon\Carbon::today()->addDays(14)->format('d-m-Y');

        Setting::add('home_postcode', 'ta63tj');

        $response = $this->call('GET', "api/bookings/block-quotes/$date_to_check/ta51ty");

        $reply = json_decode($response->getContent(), true);

        $this->assertCount( count(TimeSlots::$times) * 7 * 2 * TimeSlots::RANGE_IN_WEEKS - count(TimeSlots::$times), $reply['data']['slots'] );
    }

    /** test - slow */
    public function get_block_quotes_excludes_past_dates()
    {
        $date_to_check = Carbon\Carbon::today()->addDays(1)->format('d-m-Y');

        Setting::add('home_postcode', 'ta63tj');

        $response = $this->call('GET', "api/bookings/block-quotes/$date_to_check/ta51ty");

        $reply = json_decode($response->getContent(), true);

        $this->assertArrayHasKey(Carbon\Carbon::today()->addDays(1)->setTime(11, 0, 0)->timestamp, $reply['data']['slots']);
        $this->assertArrayNotHasKey(Carbon\Carbon::today()->setTime(11, 0, 0)->timestamp, $reply['data']['slots']);
        $this->assertArrayNotHasKey(Carbon\Carbon::today()->subDays(2)->setTime(11, 0, 0)->timestamp, $reply['data']['slots']);

        $this->assertCount( count(TimeSlots::$times) * 7 * 2 * TimeSlots::RANGE_IN_WEEKS - (count(TimeSlots::$times) * TimeSlots::RANGE_IN_WEEKS * 7), $reply['data']['slots'] );
    }

    /** @test */
    public function get_block_quotes_show_availability_correctly()
    {
        $date_to_check = Carbon\Carbon::today()->addDays(1)->format('d-m-Y');

        Setting::add('home_postcode', 'TA63TJ');

        # add a couple bookings
        $booking = $this->add_booking($date_to_check, '11:00:00', '12:00:00', \Booking::STATUS_BOOKING, 30, 'ta51th');
        $booking_2 = $this->add_booking(Carbon\Carbon::today()->addDays(4)->format('d-m-Y'), '15:00:00', '20:00:00', \Booking::STATUS_BOOKING, 30, 'ta51th');

        $response = $this->call('GET', "api/bookings/block-quotes/$date_to_check/TA43AT");

        $reply = json_decode($response->getContent(), false);

        #Log::info(print_r($reply->data->slots, true));
        #dd('done');

        $key = Carbon\Carbon::createFromFormat('d-m-Y', $booking->date)->setTime(11, 0, 0)->timestamp;
        $this->assertEquals(0, $reply->data->slots->$key->available);
        $key = Carbon\Carbon::createFromFormat('d-m-Y', $booking->date)->setTime(15, 0, 0)->timestamp;
        $this->assertEquals(1, $reply->data->slots->$key->available);
        $key = Carbon\Carbon::createFromFormat('d-m-Y', $booking->date)->setTime(19, 0, 0)->timestamp;
        $this->assertEquals(1, $reply->data->slots->$key->available);

        $key = Carbon\Carbon::createFromFormat('d-m-Y', $booking_2->date)->setTime(11, 0, 0)->timestamp;
        $this->assertEquals(1, $reply->data->slots->$key->available);
        $key = Carbon\Carbon::createFromFormat('d-m-Y', $booking_2->date)->setTime(15, 0, 0)->timestamp;
        $this->assertEquals(0, $reply->data->slots->$key->available);
        $key = Carbon\Carbon::createFromFormat('d-m-Y', $booking_2->date)->setTime(19, 0, 0)->timestamp;
        $this->assertEquals(0, $reply->data->slots->$key->available);
    }

    /** @test */
    public function make_provisional_booking_validates_inputs()
    {
        $input = [
            // missing date
            'venue_postcode' => 'tr5',
            'start_time' => '09:00',
            // missing 'finish_time' => 'required|time',
            // missing: 'venue_postcode' => 'required',
            // missing: 'package_name' => 'required',
        ];

        $response = $this->call('POST', 'api/bookings', $input);

        $this->assertTrue($this->responseHasError($response, 'date'));
        $this->assertTrue($this->responseHasError($response, 'finish_time'));
        $this->assertTrue($this->responseHasError($response, 'venue_postcode'));
        $this->assertTrue($this->responseHasError($response, 'package_name'));

        $this->assertFalse($this->responseHasError($response, 'start_time'));
    }

    /** @test */
    public function make_provisional_booking_flags_unavailable_booking()
    {
        $this->addClient();
        $booking = $this->add_booking(Carbon\Carbon::today()->addDays(10)->format('d-m-Y'), '09:00:00', '11:00:00', Booking::STATUS_BOOKING);

        $input = [
            'date' => Carbon\Carbon::today()->addDays(10)->format('d-m-Y'),
            'start_time' => '09:00',
            'finish_time' => '11:00',
            'venue_postcode' => 'ta51th',
            'package_name' => "Children's Birthday Disco",
        ];

        $response = $this->call('POST', 'api/bookings', $input);

        $this->assertTrue($this->responseHasError($response, 'availability'));
    }

    /** @test */
    public function make_provisional_booking_stores_booking()
    {
        Setting::add('home_postcode', 'ta51th');
        $this->addEmails();

        $input = [
            'date' => Carbon\Carbon::today()->addDays(10)->format('d-m-Y'),
            'start_time' => '09:00',
            'finish_time' => '11:00',
            'venue_postcode' => 'TA61TH',
            'package_name' => Package::find(1)->name,
        ];

        $response = $this->call('POST', 'api/bookings', $input);

        $reply = $this->get_api_reply($response);

        $this->assertFalse($this->responseHasError($response));

        $this->assertEquals(0, Booking::findOrFail($reply->data->booking->id)->client_id);

        $this->assertEquals('09:00:00', Booking::findOrFail($reply->data->booking->id)->start_time);
        $this->assertEquals(Package::find(1)->name, Booking::findOrFail($reply->data->booking->id)->package->name);
        $this->assertEquals(Booking::STATUS_PENDING, Booking::findOrFail($reply->data->booking->id)->status);
        $this->assertNull(Booking::findOrFail($reply->data->booking->id)->date_booked);
        $this->assertEquals(Carbon\Carbon::today()->addDays(10)->format('d-m-Y'), Booking::findOrFail($reply->data->booking->id)->date);
    }

    /** @test */
    public function make_provisional_booking_returns_booking_id()
    {
        Setting::add('home_postcode', 'ta51th');
        $this->addEmails();

        $input = [
            'date' => Carbon\Carbon::today()->addDays(10)->format('d-m-Y'),
            'start_time' => '09:00',
            'finish_time' => '11:00',
            'venue_postcode' => 'TA61TH',
            'package_name' => Package::find(1)->name,
        ];

        $response = $this->call('POST', 'api/bookings', $input);

        $reply = $this->get_api_reply($response);

        $this->assertGreaterThan(0, $reply->data->booking->id);
    }


    /** Bugs */
}
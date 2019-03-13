<?php

use Helpers\DateTimeHelper;
use Helpers\EmailHelper;

class BookNow implements BookingActionInterface {

    public $booking;

    public static $rules = [
        'date' => 'required|after:now',
        'start_time' => 'required|time',
        'finish_time' => 'required|time',
        'venue_postcode' => 'required',
        'package_name' => 'required',
        'email' => 'required|email',
        'venue_name' => 'required',
        'venue_address1' => 'required',
        'name' => 'required|name',
        'address1'=>'required',
        'address2'=>'required',
        'postcode'=>'required'
    ];

    /*
     * stored client, booking, sends contract
     */
    public function process($input = [])
    {
        $client = new Client();
        $client->name = $input['name'];
        $client->email = $input['email'];
        $client->telephone = $input['telephone'];
        $client->mobile = $input['mobile'];
        $client->heard_about = 'Booked on coolkidsparty.com';
        $client->address1 = $input['address1'];
        $client->address2 = $input['address2'];
        $client->address3 = $input['address3'];
        $client->postcode = $input['postcode'];
        $client->save();

        $booking = new Booking();
        $booking->date = $input['date'];
        $booking->start_time = DateTimeHelper::timepicker_to_dbtime($input['start_time']);
        $booking->finish_time = DateTimeHelper::timepicker_to_dbtime($input['finish_time']);
        $booking->venue_postcode = $input['venue_postcode'];
        $booking->package_id = $input['package']->id;
        $booking->setup_equipment_time = $input['package']->setup_time;
        $booking->date_booked = date('d-m-Y');
        $booking->status = \Booking::STATUS_BOOKING;
        $booking->venue_name = $input['venue_name'];
        $booking->venue_address1 = $input['venue_address1'];
        $booking->venue_address2 = $input['venue_address2'];
        $booking->venue_address3 = $input['venue_address3'];
        $booking->venue_postcode = $input['venue_postcode'];

        $costs = Costs::make($booking);
        $booking = $costs->set_costs();

        $booking->client_id = $client->id;
        $booking->save();

        $this->booking = Booking::findOrFail($booking->id);

        // send contract
        $contract = \Contract::make($this->booking->id);
        $contract->send();
    }

    public function get_rules()
    {
        return self::$rules;
    }

    public function get_unavailable_text()
    {
        return 'Unfortunately this time is no longer available.';
    }
}
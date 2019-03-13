<?php

use Helpers\DateTimeHelper;
use Helpers\EmailHelper;

class ProvisionallyBook extends CoolKidsParty implements BookingActionInterface {

    public $booking;

    public static $rules = [
        'date' => 'required|after:now',
        'start_time' => 'required|time',
        'finish_time' => 'required|time',
        'venue_postcode' => 'required|postcode',
        'package_name' => 'required',
    ];

    /*
     * stored client, booking, sends contract
     */
    public function process($input = [])
    {
        $booking = new Booking();
        $booking->date = $input['date'];
        $booking->start_time = DateTimeHelper::timepicker_to_dbtime($input['start_time']);
        $booking->finish_time = DateTimeHelper::timepicker_to_dbtime($input['finish_time']);
        $booking->venue_postcode = $input['venue_postcode'];
        $booking->package_id = $input['package']->id;
        $booking->setup_equipment_time = $input['package']->setup_time;
        $booking->status = \Booking::STATUS_PENDING;
        $booking->event_occasion = 'other';
        $booking->venue_name = '';

        $costs = Costs::make($booking);
        $booking = $costs->set_costs();

        $booking->client_id = Config::get('booking.pending_client_id', 0);
        $booking->set_id = $input['set_id'];
        $booking->save();

        $this->booking = Booking::findOrFail($booking->id);
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
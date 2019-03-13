<?php

use Helpers\DateTimeHelper;
use Helpers\EmailHelper;

class InternalQuote extends Discos implements BookingActionInterface
{
    public $booking;

    public static $rules = [
        'package_name'   => 'required',
        'occasion'       => 'required',
        'date'           => 'required|after:now',
        'start_time'     => 'required|time',
        'finish_time'    => 'required|time',
        'venue_name'     => 'required',
        'venue_postcode' => 'required',
    ];


    public function process($input = [])
    {
        $booking = new Booking();

        $booking->package_id = $input['package']->id;
        $booking->occasion = $input['occasion'];
        $booking->date = $input['date'];
        $booking->start_time = DateTimeHelper::timepicker_to_dbtime($input['start_time']);
        $booking->finish_time = DateTimeHelper::timepicker_to_dbtime($input['finish_time']);
        $booking->venue_name = $input['venue_name'];
        $booking->venue_postcode = $input['venue_postcode'];
        $booking->setup_equipment_time = $input['package']->setup_time;

        $costs = Costs::make($booking);

        $booking = $costs->set_costs();

        $booking->email_token = md5('fds5g' . rand(1, 100000) . 'hg6' . time());

        if ($booking->findDuplicate()) {
            $booking->status = \Booking::STATUS_CONFIRMATION_REQUIRED;
        } else {
            $booking->status = \Booking::STATUS_PENDING;
        }

        $booking->set_id = $input['set_id'];
        $booking->save();

        $_SESSION['booking_id'] = $booking->id;

        $this->booking = Booking::findOrFail($_SESSION['booking_id']);
    }

    public function get_rules()
    {
        return self::$rules;
    }

    public function get_unavailable_text()
    {
        return 'Unfortunately this time is not available.';
    }
}

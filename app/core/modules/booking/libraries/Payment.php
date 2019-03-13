<?php

class Payment extends CoolKidsParty implements BookingActionInterface {

    public $booking;

    public static $rules = [
        'amount' => 'required|numeric',
        'success' => 'required'
    ];

    public function __construct($booking_id)
    {
        $this->booking = Booking::findOrFail($booking_id);
    }

    public function process($input = [])
    {
        unset($this->booking->package_name);
        $this->booking->date_booked = date('d-m-Y');
        $this->booking->status = \Booking::STATUS_BOOKING;
        $this->booking->deposit_amount = $input['amount'];
        $this->booking->deposit_paid = date('d-m-Y');
        $this->booking->deposit_requested = $input['amount'];
        $this->booking->deposit_payment_method = 'Stripe Web Booking';

        $balance = $this->booking->total_cost - $input['amount'];
        $this->booking->balance_requested = $balance;

        // \Log::error(json_encode($input));

        $this->booking->save();
    }

    public function get_rules()
    {
        return self::$rules;
    }

    public function get_unavailable_text()
    {
        // TODO: Implement get_unavailable_text() method.
    }
}
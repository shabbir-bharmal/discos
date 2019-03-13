<?php

class RetrieveFromToken extends Discos implements BookingActionInterface
{
    private $token;

    public $booking;

    public static $rules = [];

    public function __construct($token)
    {
        $this->token = $token;
        $this->booking = Booking::where('email_token', $token)->where('status', Booking::STATUS_PENDING)->first();
    }

    public function process($input = [])
    {
        // nothing here
    }

    public function get_rules()
    {
        return self::$rules;
    }

    public function get_unavailable_text()
    {
        return 'Unfortunately that booking is no longer available. Please try for another time.';
    }
}
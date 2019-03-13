<?php


class Confirm extends Discos implements BookingActionInterface
{

    public $booking;

    public function __construct($booking_id)
    {
        $this->booking = Booking::findOrFail($booking_id);
    }

    public static $rules = [
        'venue_name' => 'required',
        'venue_address1' => 'required',
        'venue_postcode' => 'required|postcode',
        'event_occasion' => 'required',
        'name' => 'required|name',
        'address1' => 'required',
        'address2' => 'required',
        'postcode' => 'required'
    ];

    public function process($input = [])
    {
        unset($this->booking->package_name); // why is this set anyway??

        try {
            $this->booking->date_booked = date('d-m-Y');
            $this->booking->status = \Booking::STATUS_BOOKING;
            $this->booking->email_token .= '_USED';
            $this->booking->venue_name = $input['venue_name'];
            $this->booking->venue_address1 = $input['venue_address1'];
            $this->booking->venue_address2 = $input['venue_address2'];
            $this->booking->venue_address3 = $input['venue_address3'];
            $this->booking->venue_postcode = $input['venue_postcode'];
            $this->booking->event_occasion = $input['event_occasion'];

            if ($this->booking->event_occasion == 'wedding') {
                $this->booking->bride_firstname = $input['bride_firstname'];
                $this->booking->groom_firstname = $input['groom_firstname'];
                $this->booking->groom_surname = $input['groom_surname'];
            } else {
                $this->booking->birthday_name = $input['birthday_name'];
                $this->booking->birthday_age = $input['birthday_age'];
            }

            $this->booking->save();

            $client = Client::findOrFail($this->booking->client->id);
            $client->name = $input['name'];
            $client->address1 = $input['address1'];
            $client->address2 = $input['address2'];
            $client->address3 = $input['address3'];
            $client->postcode = $input['postcode'];
            $client->save();

            // send contract
            $contract = \Contract::make($this->booking->id);
            $contract->send();

        } catch (Exception $e) {
            Log::error('Error confirming booking: ' . $e->getMessage());
            return false;
        }

        return true;
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
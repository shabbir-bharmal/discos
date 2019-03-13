<?php


class CoolKidsConfirm extends CoolKidsParty implements BookingActionInterface
{

    public $booking;

    public function __construct($booking_id)
    {
        $this->booking = Booking::findOrFail($booking_id);
    }

    public static $rules = [
        'venue_name' => 'required',
        'venue_address1' => 'required',
        'event_occasion' => 'required',
        'name' => 'required|name',
        'address1' => 'required',
        'address2' => 'required',
        'postcode' => 'required',
        'email' => 'required|email',
        'event_occasion' => 'required'
    ];

    public function process($input = [])
    {
        unset($this->booking->package_name); // why is this set anyway??

        try {
            $this->booking->venue_name = $input['venue_name'];
            $this->booking->venue_address1 = $input['venue_address1'];
            $this->booking->venue_address2 = $input['venue_address2'];
            $this->booking->venue_address3 = $input['venue_address3'];
            $this->booking->event_occasion = $input['event_occasion'];
			if(isset($input['deposit_paid'])){
				$this->booking->deposit_paid 	= $input['deposit_paid'];
			}
			if(isset($input['date_booked'])){
				$this->booking->date_booked 	= $input['date_booked'];
			}
			if(isset($input['deposit_amount'])){
				$this->booking->deposit_amount 	= $input['deposit_amount'];
			}
			if(isset($input['deposit_payment_method'])){
				$this->booking->deposit_payment_method 	= $input['deposit_payment_method'];
			}
			// if(isset($input['balance_requested'])){
				// $this->booking->balance_requested 	= $input['balance_requested'];
			// }
			if(isset($input['event_payment_status'])){
				$this->booking->status 	= $input['event_payment_status'];
			}

            if ($this->booking->event_occasion == 'birthday') {
                $this->booking->birthday_name = $input['birthday_name'];
                $this->booking->birthday_age = $input['birthday_age'];
            }

            $client = Client::storeClient([
                'name' => $input['name'],
                'email' => $input['email'],
                'telephone' => $input['telephone'],
                'mobile' => $input['mobile'],
                'heard_about' => 'Booked on coolkidsparty.com',
                'address1' => $input['address1'],
                'address2' => $input['address2'],
                'address3' => $input['address3'],
                'postcode' => $input['postcode'],
                'address4' => isset($input['county']) ? $input['county'] : '',
            ]);

            $this->booking->client_id = $client->id;
            $this->booking->save();

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
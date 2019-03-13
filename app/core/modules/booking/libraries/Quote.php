<?php

use Helpers\DateTimeHelper;

use Helpers\EmailHelper;

class Quote extends Discos implements BookingActionInterface
{



    public $booking;



    public static $rules = [

        'date' => 'required|after:now',

        'start_time' => 'required|time',

        'finish_time' => 'required|time',

        'venue_name' => 'required',

        'venue_postcode' => 'required',

        'package_name' => 'required',

        'name' => 'required',

        'email' => 'required|email',

    ];



    public function process($input = [])
    {

        $client = Client::storeClient([

            'name' => $input['name'],

            'email' => $input['email'],

            'telephone' => $input['telephone'],

            'mobile' => $input['mobile'],

            'heard_about' => $input['heard_about'],

        ]);



        $booking = new Booking();

        $booking->date = $input['date'];

        $booking->start_time = DateTimeHelper::timepicker_to_dbtime($input['start_time']);
        $booking->finish_time = DateTimeHelper::timepicker_to_dbtime($input['finish_time']);

        $booking->venue_name = $input['venue_name'];

        $booking->venue_postcode = $input['venue_postcode'];

        $booking->package_id = $input['package']->id;

        $booking->setup_equipment_time = $input['package']->setup_time;



        $costs = Costs::make($booking);

        $booking = $costs->set_costs();



        $booking->email_token = md5('fds5g' . rand(1, 100000) . 'hg6' . time());

        $booking->client_id = $client->id;

        $booking->ref_no = $input['ref_no'];

        if ($booking->findDuplicate()) {
            $booking->status = \Booking::STATUS_CONFIRMATION_REQUIRED;
        } else {
            $booking->status = \Booking::STATUS_PENDING;
        }

        $booking->set_id = $input['set_id'];

        $booking->save();

        $_SESSION['booking_id'] = $booking->id;



        $this->booking = Booking::findOrFail($_SESSION['booking_id']);



        if ($booking->status == \Booking::STATUS_PENDING) {
            $email = $booking->package->emailtemplate;

            $email_template = EmailHelper::get_email_viewname($email);

            $date = new \DateTime($input['date']);

            $booking->timestamp = $date->getTimestamp();

            $this->email = array('link' => \Config::get('app.url') . 'booking/' . $booking->email_token);

            // below fix needed because substr won't save on live!
            $startpahar = (substr($booking->start_time, 0, -6)<13)?'AM':'PM';
            $finishpahar = (substr($booking->finish_time, 0, -6)<13)?'AM':'PM';
            $booking->start_time_formatted = (substr($booking->start_time, 0, -6)%12).":".substr($booking->start_time, 3, -3)." ".$startpahar;

            $booking->finish_time_formatted = (substr($booking->finish_time, 0, -6)%12).":".substr($booking->finish_time, 3, -3)." ".$finishpahar;

            /*$booking->start_time_formatted = substr($booking->start_time, 0, -3);

            $booking->finish_time_formatted = substr($booking->finish_time, 0, -3);
*/
            # send email to client if this is a quotation

            EmailHelper::sendQuotation($client->email, $email_template, compact('client', 'booking', 'email'), $client, $booking, $email);

            // Save email to follow ups table
            FollowUp::create(['client_id' => $client->id, 'booking_id' => $booking->id]);
        } else {
            Mail::send('templates.emails.confirmation', compact('booking'), function ($message) use ($booking) {
            	// Date 4/5/2018 - sdp
                //$message->to($booking->package->emailtemplate->email_from, $booking->package->emailtemplate->name_from);
                $message->to(Setting::where('key', '=', 'admin_email')->first()->value, $booking->package->emailtemplate->name_from);
            
                $message->subject('Booking confirmation required');
            });
        }
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

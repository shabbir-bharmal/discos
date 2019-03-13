<?php

/*
 * 
 */
trait TestBookingTrait
{
    public function addBooking(Client $client, Package $package)
    {        
        $booking = new Booking;
        $booking->client_id = $client->id;
        $booking->package_id = $package->id;
        $booking->date_booked = date('Y-m-d');
        $booking->date = date('Y-m-d');
        $booking->start_time = '12:00';
        $booking->finish_time = '14:00';
        $booking->venue_name = 'venue';
        $booking->venue_postcode = 'WE456TR';
        $booking->event_occasion = 'wedding';
        $booking->deposit_requested = 20;
        $booking->balance_requested = 100;
        $booking->total_cost = 120;
        $booking->status = \Booking::STATUS_BOOKING;
        $booking->deleted = 0;
        $booking->save();
        
        return $booking;
    }
    
    public function add_booking($date, $start, $finish, $status, $setup = 30, $postcode = 'TA51nf', $package_id = 1)
    {        
        $booking = new \Booking();
        $booking->date = $date;
        $booking->date_booked = date('d-m-Y');
        $booking->event_occasion = 'wedding';
        $booking->start_time = $start;            
        $booking->finish_time = $finish;
        $booking->venue_name = 'Venue';
        $booking->venue_postcode = $postcode;
        $booking->package_id = $package_id;
        $booking->setup_equipment_time = $setup;
        $booking->total_cost = 100;
        $booking->deposit_requested = 20;
        $booking->balance_requested = 80;
        $booking->email_token = md5('fds5g'.rand(1,100000).'hg6'.time());
        $booking->client_id = 1;
        $booking->status = $status;
        $booking->save();

        return $booking;
    }
}

?>

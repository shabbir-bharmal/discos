<?php

class AdminTest extends TestCase
{
    public function testCorrectBookingDate()
    {
        $today = new DateTime;
        
        // add client
        $client = new Client;
        $client->name = 'jo';
        $client->email = 'jo@jo.jo';
        $client->telephone = '123';
        $client->mobile = '123';
        $client->address1 = '123';
        $client->address2 = '123';
        $client->postcode = '123';
        $client->save();
        
        // add booking
        $booking = new Booking;
        $booking->client_id = $client->id;
        $booking->package_id = 1;
        $booking->date_booked = $today->format('Y-m-d');
        
        $today->add(new DateInterval('P2D'));
        $booking->date = $today->format('Y-m-d');
        $booking->start_time = '12312';
        $booking->finish_time = '23423';
        $booking->venue_name = 'werwr';
        $booking->venue_postcode = 'werwr';
        $booking->event_occasion = 'werwr';
        $booking->status = \Booking::STATUS_BOOKING;
        $booking->deposit_requested = 0;
        $booking->balance_requested = 0;
        $booking->total_cost = 0;
        $booking->save();
        
        
        $this->assertTrue($client->hasActiveBookings());
    }
}

?>

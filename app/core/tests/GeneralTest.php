<?php

class GeneralTest extends TestCase
{
    use TestBookingTrait;
    use TestClientTrait;
    
    public function testBladeStrpos()
    {
        $var = "something Marquee something";
        
        $this->assertGreaterThan(0, strpos($var, 'Marquee'));
        $this->assertEquals(true, strpos($var, 'Marquee'));
    }
    
    /** test */
    public function travel_time_bug()
    {        
        $setting = new Setting();
        $setting->key = 'home_postcode';
        $setting->value = 'TA63TJ';
        $setting->notes = 'Postcode used in quotation calculations';
        $setting->save();
        
        $client = $this->addClient();        
        $booking = $this->addBooking($client, Package::find(1));
        
        $booking->start_time = '12:30:00';
        $booking->finish_time = '14:30:00';
        $booking->venue_postcode = 'PL4 8HN';
        $booking->save();
        
        $timestamp = $booking->start_timestamp;
        $timestamp -= (60 * $booking->package->setup_time); 
        $timestamp -= (60 * $booking->travel_time); 
        $pickup = strftime("%H:%M", $timestamp);
        
        
        $timestamp = $booking->finish_timestamp;
        $timestamp += (60 * $booking->package->setup_time); 
        $timestamp += (60 * $booking->travel_time); 
        $dropoff = strftime("%H:%M", $timestamp);
   
   
        
        $this->assertEquals('16:45', $dropoff);
        $this->assertEquals('10:15', $pickup);
    }
}

?>

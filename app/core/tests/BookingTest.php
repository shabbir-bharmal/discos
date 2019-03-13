<?php

use Helpers\DateTimeHelper;
use Helpers\PackageHelper;

class BookingTest extends TestCase
{
    use TestBookingTrait;
    use TestClientTrait;
    use TestEmailTrait;
    
    public function testCorrectBookingDate()
    {
        $input['date'] = '15-01-2015';
        $input['date'] = DateTimeHelper::us_to_uk_date($input['date'], '-');
        
        $booking_date_obj = new \DateTime($input['date']);
        
        $timestamp = $booking_date_obj->getTimestamp();
        
        $this->assertEquals('15-01-2015', date('d-m-Y', $timestamp));
    }
    
    public function testDatesOnAdd()
    {
        $input['client_id'] = 1;
        $input['package_id'] = 1;
        $input['start_time'] = '';
        $input['finish_time'] = '';
        $input['venue_name'] = '';
        $input['venue_postcode'] = '';
        $input['event_occasion'] = '';
        $input['deposit_requested'] = 0;
        $input['balance_requested'] = 0;
        $input['total_cost'] = 0;
        $input['status'] = 'test';
        $input['created_at'] = '';
        $input['updated_at'] = '';
        
        $input['date'] = '01-07-2014';
        $input['date_booked'] = '24-06-2014';
        $input['deposit_paid'] = '15-03-2014';
        $input['balance_paid'] = '23-04-2014';
        
        $id = \Booking::insertGetId($input);
        
        $booking = \Booking::find($id);
        
        $this->assertEquals('01-07-2014', $booking->date);
        $this->assertEquals('24-06-2014', $booking->date_booked);
        $this->assertEquals('15-03-2014', $booking->deposit_paid);
        $this->assertEquals('23-04-2014', $booking->balance_paid);
    }
    
    /** @test */
    public function event_fired_on_changing_status()
    {
        $this->results = [];
        
        Event::listen('booking.status_changed', function($before, $after) {
            
            $this->results[] = "status changed from $before to $after\n";
            
        });
        
        $client = $this->addClient();
        $booking = $this->addBooking($client, Package::find(1));
        
        $booking->status = \Booking::STATUS_PENDING;
        $booking->save();
        
        $booking->status = \Booking::STATUS_BOOKING;
        $booking->save();
        
        $booking->status = \Booking::STATUS_PENDING;
        $booking->save();
        
        $booking->status = \Booking::STATUS_BOOKING;
        $booking->save();
        
        
        $this->assertCount(2, $this->results);
        $this->assertEquals("status changed from pending to booking\n", $this->results[0]);
        $this->assertEquals("status changed from pending to booking\n", $this->results[1]);
    }
    
    /** @test */
    public function email_sent_on_changing_status()
    {      
        $regular = new EmailTemplate;
        $regular->type = EmailTemplate::TYPE_EVENT_STATUS_CHANGED;
        $regular->name = 'Status changed';
        $regular->recipient = 'test@email.com';
        $regular->subject = 'Status changed!';
        $regular->view = 'test';
        $regular->filter = 'event';
        $regular->email_from = 'ask@coolkidsparty.com';
        $regular->name_from = 'Cool Kids Party';
        $regular->data = 'current';
        $regular->packages = range(1,20);
        $regular->save();        
        
        $this->emails_sent = array();
        
        Event::listen('mailer.sending', function($mail){
            $this->emails_sent[] = $mail;
        });
        
        $client = $this->addClient();
        $booking = $this->addBooking($client, Package::find(1));
        
        $booking->status = \Booking::STATUS_PENDING;
        $booking->save();
        
        $booking->status = \Booking::STATUS_BOOKING;
        $booking->save();
        
        
        $this->assertCount(1, $this->emails_sent);
    }
}

?>

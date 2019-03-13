<?php

use Helpers\DateTimeHelper;
use Helpers\PackageHelper;

class AvailabilityTest extends TestCase {
    
    use TestBookingTrait;
    
    public function testAvailable_pastMidnight()
    {        
        $this->add_booking('16-08-2014', '12:00:00', '14:00:00', \Booking::STATUS_BOOKING, 45, 'TA7 8BY');
        
        $date = new DateTime('2014-08-16');
        list($start, $end) = DateTimeHelper::get_start_end_timestamps($date, '18:30:00', '01:00:00');
        
        #echo "\n new booking date: ".$date->format('Y-m-d');
        
        $availability = \Availability::make( array (
            'date' => $date,
            'start_timestamp' => $start,
            'finish_timestamp' => $end,
            'package_name' => 'Children\'s Party Disco',
            'postcode' => 'TA3 6EU'
        ) );
        
        $availability->package_id = 1;
        
        $this->assertTrue($availability->is_free_from_other_bookings());
    }

	public function testAvailable_1()
    {        
        $this->add_booking('16-08-2014', '12:00:00', '14:00:00', \Booking::STATUS_BOOKING, 45, 'TA7 8BY');
        
        $date = new DateTime('2014-08-16');
        list($start, $end) = DateTimeHelper::get_start_end_timestamps($date, '18:30:00', '23:00:00');
        
        #echo "\n new booking date: ".$date->format('Y-m-d');
        
        $availability = \Availability::make( array (
            'date' => $date,
            'start_timestamp' => $start,
            'finish_timestamp' => $end,
            'package_name' => 'Children\'s Party Disco',
            'postcode' => 'TA3 6EU',
            'debug' =>false
        ) );
        
        $availability->package_id = 1;
        
        $this->assertTrue($availability->is_free_from_other_bookings());
    }

	public function testAvailable_2_1()
    {
        $this->add_booking('16-08-2014', '8:00:00', '12:00:00', \Booking::STATUS_BOOKING, 45, 'TA63TJ');
        
        $date = new DateTime('2014-08-16');
        list($start, $end) = DateTimeHelper::get_start_end_timestamps($date, '13:30:00', '20:00:00');
        
        #echo "\n new booking date: ".$date->format('Y-m-d');
        
        $availability = \Availability::make( array (
            'date' => $date,
            'start_timestamp' => $start,
            'finish_timestamp' => $end,
            'package_name' => 'Primary School Disco',
            'postcode' => 'BS229YX', // distance = 39 mins
            'debug' =>false
        ) );
        
        $availability->package_id = 7; // setup = 30
        
        $this->assertFalse($availability->is_free_from_other_bookings());
    }

	public function testAvailable_2_2()
    {
        $this->add_booking('16-08-2014', '8:00:00', '12:00:00', \Booking::STATUS_BOOKING, 45, 'TA63TJ');
        
        $date = new DateTime('2014-08-16');
        list($start, $end) = DateTimeHelper::get_start_end_timestamps($date, '14:00:00', '20:00:00');
        
        #echo "\n new booking date: ".$date->format('Y-m-d');
        
        $availability = \Availability::make( array (
            'date' => $date,
            'start_timestamp' => $start,
            'finish_timestamp' => $end,
            'package_name' => 'Primary School Disco',
            'postcode' => 'BS229YX', // distance = 39 mins
            'debug' =>false
        ) );
        
        $availability->package_id = 7; // setup = 30
        
        $this->assertTrue($availability->is_free_from_other_bookings());
    }

	public function testAvailable_3_1()
    {
        $this->add_booking('16-08-2014', '13:00:00', '18:00:00', \Booking::STATUS_BOOKING, 45, 'TA63TJ');
        
        $date = new DateTime('2014-08-16');
        list($start, $end) = DateTimeHelper::get_start_end_timestamps($date, '09:00:00', '11:00:00');
        
        #echo "\n new booking date: ".$date->format('Y-m-d');
        
        $availability = \Availability::make( array (
            'date' => $date,
            'start_timestamp' => $start,
            'finish_timestamp' => $end,
            'package_name' => 'Primary School Disco',
            'postcode' => 'BS229YX', // distance = 39 mins
            'debug' =>false
        ) );
        
        $availability->package_id = 7; // setup = 30
        
        $this->assertTrue($availability->is_free_from_other_bookings());
    }

	public function testAvailable_3_2()
    {
        $this->add_booking('16-08-2014', '13:00:00', '18:00:00', \Booking::STATUS_BOOKING, 45, 'TA63TJ');
        
        $date = new DateTime('2014-08-16');
        list($start, $end) = DateTimeHelper::get_start_end_timestamps($date, '9:00:00', '11:30:00');
        $date->setTime(9, 00, 0);
        $start = $date->getTimestamp();
        $date->setTime(11, 30, 0);
        $end = $date->getTimestamp();
        
        #echo "\n new booking date: ".$date->format('Y-m-d');
        
        $availability = \Availability::make( array (
            'date' => $date,
            'start_timestamp' => $start,
            'finish_timestamp' => $end,
            'package_name' => 'Primary School Disco',
            'postcode' => 'BS229YX', // distance = 39 mins
            'debug' =>false
        ) );
        
        $availability->package_id = 7; // setup = 30
        
        $this->assertFalse($availability->is_free_from_other_bookings());
    }

	public function testAvailable_exclusionBlock()
    {
        $rule = new \Rule;
        $rule->name = 'exclusion';
        $rule->date_from = '2014-01-01';
        $rule->date_to = '2014-02-28';
        $rule->package_id = 0;
        $rule->save();
        
        $date = new DateTime('2014-01-16');
        list($start, $end) = DateTimeHelper::get_start_end_timestamps($date, '9:00:00', '11:30:00');
        $date->setTime(9, 00, 0);
        $start = $date->getTimestamp();
        $date->setTime(11, 30, 0);
        $end = $date->getTimestamp();
        
        $availability = \Availability::make( array (
            'date' => $date,
            'start_timestamp' => $start,
            'finish_timestamp' => $end,
            'package_name' => 'Primary School Disco',
            'postcode' => 'BS229YX', // distance = 39 mins
        ) );
        
        $this->assertFalse($availability->has_package());
        $this->assertEquals(0, $availability->package_id);
    }


}

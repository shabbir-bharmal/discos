<?php

use Helpers\PackageHelper;

class QuotationTest extends TestCase {

    public function testTravelCosts_1()
    {
        $package = \Package::find(1);
        $this->assertEquals(0, PackageHelper::get_travel_cost($package, 30));
    }
    
    public function testTravelCosts_2()
    {
        $package = \Package::find(1);
        $this->assertEquals(15, PackageHelper::get_travel_cost($package, 60));
    }
    public function testTravelCosts_3()
    {
        $package = \Package::find(13); //'free_travel'=>'60', 'travel_cost'=>25,'travel_interval'=>'30'
        $this->assertEquals(25, number_format(PackageHelper::get_travel_cost($package, 100), 2));
    }
	
	public function testCorrectPackageFromRequest_1()
	{        
        $date = new DateTime();
        $date->setDate('2014', '07', '03'); // thursday
        
        $package_id = PackageHelper::get_package_id_from_request('Children\'s Party Disco', $date, '08:00', '10:00');
        
        $this->assertEquals(1, intval($package_id));
	}
 	
	public function testCorrectPackageFromRequest_2()
	{        
        $date = new DateTime();
        $date->setDate('2014', '07', '04'); // fri
        
        $package_id = PackageHelper::get_package_id_from_request('Children\'s Party Disco', $date, '12:00', '16:00');
        
        $this->assertEquals(2, intval($package_id));
    }
    
	public function testCorrectPackageFromRequest_3()
	{        
        $date = new DateTime();
        $date->setDate('2014', '07', '04'); // fri
        
        $package_id = PackageHelper::get_package_id_from_request('Children\'s Party Disco', $date, '16:30', '19:00');
        
        $this->assertEquals(3, intval($package_id));
	}   
    	
	public function testCorrectPackageFromRequest_4()
	{        
        $date = new DateTime();
        $date->setDate('2014', '07', '05'); // sat
        
        $package_id = PackageHelper::get_package_id_from_request('Children\'s Party Disco', $date, '08:00', '10:00');
        
        $this->assertEquals(4, intval($package_id));
	}
    	
	public function testCorrectPackageFromRequest_5()
	{        
        $date = new DateTime();
        $date->setDate('2014', '07', '05'); // sat
        
        $package_id = PackageHelper::get_package_id_from_request('Children\'s Party Disco', $date, '17:00', '18:00');
        
        $this->assertEquals(5, intval($package_id));
	}
    	
	public function testCorrectPackageFromRequest_6()
	{        
        $date = new DateTime();
        $date->setDate('2014', '07', '06'); // sun
        
        $package_id = PackageHelper::get_package_id_from_request('Children\'s Party Disco', $date, '07:00', '10:00');
        
        $this->assertEquals(6, intval($package_id));
	}
    	
	public function testCorrectPackageFromRequest_7()
	{        
        $date = new DateTime();
        $date->setDate('2014', '07', '01'); // tues
        
        $package_id = PackageHelper::get_package_id_from_request('Primary School Disco', $date, '08:00', '10:00');
        
        $this->assertEquals(7, intval($package_id));
	}
    	
	public function testCorrectPackageFromRequest_8()
	{        
        $date = new DateTime();
        $date->setDate('2014', '07', '04'); // fri
        
        $package_id = PackageHelper::get_package_id_from_request('Primary School Disco', $date, '07:30', '10:00');
        
        $this->assertEquals(8, intval($package_id));
	}
    	
	public function testCorrectPackageFromRequest_9()
	{        
        $date = new DateTime();
        $date->setDate('2014', '07', '04'); // fri
        
        $package_id = PackageHelper::get_package_id_from_request('Primary School Disco', $date, '17:00', '21:00');
        
        $this->assertEquals(9, intval($package_id));
	}
    	
	public function testCorrectPackageFromRequest_10()
	{        
        $date = new DateTime();
        $date->setDate('2014', '07', '05'); // sat
        
        $package_id = PackageHelper::get_package_id_from_request('Primary School Disco', $date, '08:15', '10:00');
        
        $this->assertEquals(10, intval($package_id));
	}
    	
	public function testCorrectPackageFromRequest_11()
	{        
        $date = new DateTime();
        $date->setDate('2014', '07', '05'); // sat
        
        $package_id = PackageHelper::get_package_id_from_request('Primary School Disco', $date, '18:00', '19:30');
        
        $this->assertEquals(0, intval($package_id));
	}
    	
	public function testCorrectPackageFromRequest_12()
	{        
        $date = new DateTime();
        $date->setDate('2014', '07', '06'); // sun
        
        $package_id = PackageHelper::get_package_id_from_request('Primary School Disco', $date, '12:00', '14:00');
        
        $this->assertEquals(12, intval($package_id));
	}
    	
	public function testCorrectPackageFromRequest_13()
	{        
        $date = new DateTime();
        $date->setDate('2014', '07', '03'); // thur
        
        $package_id = PackageHelper::get_package_id_from_request('Wedding Reception', $date, '12:00', '14:00');
        
        $this->assertEquals(13, intval($package_id));
	}
    	
	public function testCorrectPackageFromRequest_14()
	{        
        $date = new DateTime();
        $date->setDate('2014', '07', '04'); // fri
        
        $package_id = PackageHelper::get_package_id_from_request('Wedding Reception', $date, '12:00', '14:00');
        
        $this->assertEquals(14, intval($package_id));
	}
    	
	public function testCorrectPackageFromRequest_14_2()
	{        
        $date = new DateTime();
        $date->setDate('2014', '08', '29'); // fri
        
        $package_id = PackageHelper::get_package_id_from_request('Wedding Reception', $date, '08:00', '16:00');
        
        $this->assertEquals(14, intval($package_id));
	}
    	
	public function testCorrectPackageFromRequest_15()
	{        
        $date = new DateTime();
        $date->setDate('2014', '07', '05'); // sat
        
        $package_id = PackageHelper::get_package_id_from_request('Wedding Reception', $date, '08:00', '21:00');
        
        $this->assertEquals(15, intval($package_id));
	}
    	
	public function testCorrectPackageFromRequest_16()
	{        
        $date = new DateTime();
        $date->setDate('2014', '07', '06'); // sun
        
        $package_id = PackageHelper::get_package_id_from_request('Wedding Reception', $date, '12:00', '14:00');
        
        $this->assertEquals(16, intval($package_id));
	}
    	
	public function testCorrectPackageFromRequest_17()
	{        
        $date = new DateTime();
        $date->setDate('2014', '07', '07'); // mon
        
        $package_id = PackageHelper::get_package_id_from_request('Others', $date, '12:00', '14:00');
        
        $this->assertEquals(17, intval($package_id));
	}
    	
	public function testCorrectPackageFromRequest_18()
	{        
        $date = new DateTime();
        $date->setDate('2014', '07', '04'); // fri
        
        $package_id = PackageHelper::get_package_id_from_request('Others', $date, '12:00', '14:00');
        
        $this->assertEquals(18, intval($package_id));
	}
    	
	public function testCorrectPackageFromRequest_19()
	{        
        $date = new DateTime();
        $date->setDate('2014', '07', '05'); // sat
        
        $package_id = PackageHelper::get_package_id_from_request('Others', $date, '12:00', '14:00');
        
        $this->assertEquals(19, intval($package_id));
	}
    	
	public function testCorrectPackageFromRequest_20()
	{        
        $date = new DateTime();
        $date->setDate('2014', '07', '06'); // sun
        
        $package_id = PackageHelper::get_package_id_from_request('Others', $date, '12:00', '14:00');
        
        $this->assertEquals(20, intval($package_id));
	}
    	
	public function testCorrectPackageFromRequest_21()
	{        
        $date = new DateTime();
        $date->setDate('2014', '08', '16'); // sat
        
        $package_id = PackageHelper::get_package_id_from_request('Children\'s Party Disco', $date, '18:00:00', '00:00:00');
        
        $this->assertEquals(5, intval($package_id));
	}
    	
	public function testCorrectPackageOverTwoSlots_1()
	{        
        $date = new DateTime();
        $date->setDate('2014', '07', '19'); // sat
        
        $package_id = PackageHelper::get_package_id_from_request('Children\'s Party Disco', $date, '15:00', '18:30');
        
        $this->assertEquals(5, intval($package_id));
	}
    	
	public function testCorrectPackageOverTwoSlots_2()
	{        
        $date = new DateTime();
        $date->setDate('2014', '07', '19'); // sat
        
        $package_id = PackageHelper::get_package_id_from_request('Children\'s Party Disco', $date, '15:00', '18:00');
        
        $this->assertEquals(5, intval($package_id));
	}  
    
    /*
     * costings tests
     */    
    
    private function get_booking_id_1()
    {
        $booking = new \Booking();
        $booking->date = '2014-01-01';
        $booking->event_occasion = 'wedding';
        $booking->start_time = '10:00:00';            
        $booking->finish_time = '12:00:00';
        $booking->venue_name = 'Vanue';
        $booking->venue_postcode = 'POST CODE';
        $booking->package_id = 1;
        $booking->total_cost = 100;
        $booking->balance_amount = 100;
        $booking->deposit_amount = 25;
        $booking->email_token = md5('fds5g'.rand(1,100000).'hg6'.time());
        $booking->client_id = 1;
        $booking->status = \Booking::STATUS_PENDING;
        $booking->save();
        
        return $booking->id;
    }
    
    public function testCorrectCostFromRequest_1()
	{        
        #cost should be: 120 + (2 * 15) + (20/30 * 15)
        
        $this->assertEquals(150, PackageHelper::calculate_booking_cost(1, 3, 50));
	}   
    
    private function get_booking_id_2()
    {
        $booking = new \Booking();
        $booking->date = '2014-01-01';
        $booking->event_occasion = 'wedding';
        $booking->start_time = '10:00:00';            
        $booking->finish_time = '12:00:00';
        $booking->venue_name = 'Vanue';
        $booking->venue_postcode = 'POST CODE';
        $booking->package_id = 2;
        $booking->total_cost = 100;
        $booking->balance_amount = 100;
        $booking->deposit_amount = 25;
        $booking->email_token = md5('fds5g'.rand(1,100000).'hg6'.time());
        $booking->client_id = 1;
        $booking->status = \Booking::STATUS_PENDING;
        $booking->save();
        
        return $booking->id;
    }
    
    public function testCorrectCostFromRequest_2()
	{        
        $this->assertEquals(120, PackageHelper::calculate_booking_cost(2, 1, 30));
	}   
    
    private function get_booking_id_3()
    {
        $booking = new \Booking();
        $booking->date = '2014-01-01';
        $booking->event_occasion = 'wedding';
        $booking->start_time = '10:00:00';            
        $booking->finish_time = '12:00:00';
        $booking->venue_name = 'Vanue';
        $booking->venue_postcode = 'POST CODE';
        $booking->package_id = 3;
        $booking->total_cost = 100;
        $booking->balance_amount = 100;
        $booking->deposit_amount = 25;
        $booking->email_token = md5('fds5g'.rand(1,100000).'hg6'.time());
        $booking->client_id = 1;
        $booking->status = \Booking::STATUS_PENDING;
        $booking->save();
        
        return $booking->id;
    }
    
    public function testCorrectCostFromRequest_3()
	{
        #cost: 150 + (3/0.5 * 15) + (70/30 * 15) => 200 + 30
        
        $this->assertEquals(230, PackageHelper::calculate_booking_cost(3, 5, 100));
	}  
    
    private function get_booking_id_4()
    {
        $booking = new \Booking();
        $booking->date = '2014-01-01';
        $booking->event_occasion = 'wedding';
        $booking->start_time = '10:00:00';            
        $booking->finish_time = '12:00:00';
        $booking->venue_name = 'Vanue';
        $booking->venue_postcode = 'POST CODE';
        $booking->package_id = 4;
        $booking->total_cost = 100;
        $booking->balance_amount = 100;
        $booking->deposit_amount = 25;
        $booking->email_token = md5('fds5g'.rand(1,100000).'hg6'.time());
        $booking->client_id = 1;
        $booking->status = \Booking::STATUS_PENDING;
        $booking->save();
        
        return $booking->id;
    }
    
    public function testCorrectCostFromRequest_4()
	{
        #cost: max(120 + (2/0.5 * 15), 200) + (0/30 * 15) => 180 + 0
        
        $this->assertEquals(180, PackageHelper::calculate_booking_cost(4, 4, 10));
	} 
    
    private function get_booking_id_5()
    {
        $booking = new \Booking();
        $booking->date = '2014-01-01';
        $booking->event_occasion = 'wedding';
        $booking->start_time = '10:00:00';            
        $booking->finish_time = '12:00:00';
        $booking->venue_name = 'Vanue';
        $booking->venue_postcode = 'POST CODE';
        $booking->package_id = 5;
        $booking->total_cost = 100;
        $booking->balance_amount = 100;
        $booking->deposit_amount = 25;
        $booking->email_token = md5('fds5g'.rand(1,100000).'hg6'.time());
        $booking->client_id = 1;
        $booking->status = \Booking::STATUS_PENDING;
        $booking->save();
        
        return $booking->id;
    }
    
    public function testCorrectCostFromRequest_5()
	{
        #cost: max(175 + (2/0.5 * 15), 200) + (0/30 * 15) => 200 + 0
        
        $this->assertEquals(200, PackageHelper::calculate_booking_cost(5, 4, 10));
	}
    
    
    private function get_booking_id_15()
    {
        $booking = new \Booking();
        $booking->date = '2014-01-01';
        $booking->event_occasion = 'wedding';
        $booking->start_time = '10:00:00';            
        $booking->finish_time = '12:00:00';
        $booking->venue_name = 'Vanue';
        $booking->venue_postcode = 'POST CODE';
        $booking->package_id = 15;
        $booking->total_cost = 100;
        $booking->balance_amount = 100;
        $booking->deposit_amount = 25;
        $booking->email_token = md5('fds5g'.rand(1,100000).'hg6'.time());
        $booking->client_id = 1;
        $booking->status = \Booking::STATUS_PENDING;
        $booking->save();
        
        return $booking->id;
    }
    
    public function testCorrectCostFromRequest_15()
	{
        #cost: max(350 + ( (1 * 60)/30 * 25), 500) + (10/30 * 25) => 400 + 0
        
        $this->assertEquals(400, PackageHelper::calculate_booking_cost(15, 6, 70));
	}
    
    private function set_package_16()
    {
        $package = new \Package();
        $package->name = 'test';
        $package->day = 'Sun';
        $package->start_time = '08:00';
        $package->finish_time = '04:00';
        $package->min_price = 120;
        $package->max_price = 200;
        $package->hours_inc = 2;
        $package->overtime_cost = 15;
        $package->overtime_interval = 30;
        $package->free_travel = 30;
        $package->travel_cost = 15;
        $package->travel_interval = 30;
        $package->setup_time = 45;
        $package->email_template_id = 1;
        $package->save();
        
        return $package;
    }
    
    public function testCorrectCostFromRequest_16()
	{
        $package = $this->set_package_16();
        
        $distance_in_minutes = PackageHelper::get_minutes_between_postcodes('TA63TJ', "BS16 5UE"); #54
        
        # extra hours: 0
        $this->assertEquals($package->min_price, PackageHelper::get_disco_cost($package, 2));
        
        # extra travel: 54 - 30 = 24 => (24 / 30) * 15 => 0
        $this->assertEquals(0, PackageHelper::get_travel_cost($package, $distance_in_minutes));
        
        $this->assertEquals(120, PackageHelper::calculate_booking_cost($package->id, 2, $distance_in_minutes));
	}
    	
	public function testRules_xmass()
	{
        $package = $this->set_package_16();
        
        $rule = new \Rule;
        $rule->name = 'xmas';
        $rule->date_from = '2014-12-25';
        $rule->date_to = '2014-12-25';
        $rule->package_id = $package->id;
        $rule->save();
        
        $date = new DateTime();
        $date->setDate('2014', '12', '25'); 
        
        $package_id = PackageHelper::get_package_id_from_request('Children\'s Party Disco', $date, '15:00', '18:00');
        
        $this->assertEquals($package->id, intval($package_id));
	}
    
	public function testRules_inrange()
	{
        $package = $this->set_package_16();
        
        $rule = new \Rule;
        $rule->name = 'xmas';
        $rule->date_from = '2014-01-01';
        $rule->date_to = '2014-02-28';
        $rule->package_id = $package->id;
        $rule->save();
        
        $date = new DateTime();
        $date->setDate('2014', '01', '25'); 
        
        $package_id = PackageHelper::get_package_id_from_request('Children\'s Party Disco', $date, '15:00', '18:00');
        
        $this->assertEquals($package->id, intval($package_id));
	}
    
	public function testRules_outrange()
	{
        $package = $this->set_package_16();
        
        $rule = new \Rule;
        $rule->name = 'xmas';
        $rule->date_from = '2014-01-01';
        $rule->date_to = '2014-02-28';
        $rule->package_id = $package->id;
        $rule->save();
        
        $date = new DateTime();
        $date->setDate('2014', '12', '25'); 
        
        $package_id = PackageHelper::get_package_id_from_request('Children\'s Party Disco', $date, '15:00', '18:00');
        
        $this->assertNotEquals($package->id, intval($package_id));
	}    
	
	public function testRules_nopackage()
	{        
        $rule = new \Rule;
        $rule->name = 'exclusion';
        $rule->date_from = '2014-01-01';
        $rule->date_to = '2014-02-28';
        $rule->package_id = 0;
        $rule->save();
        
        $date = new DateTime();
        $date->setDate('2014', '01', '25'); 
        
        $package_id = PackageHelper::get_package_id_from_request('Children\'s Party Disco', $date, '15:00', '18:00');
        
        $this->assertEquals(0, intval($package_id));
	}
    
    public function testQueryonCost()
	{
        $package = new \Package();
        $package->name = 'test';
        $package->day = 'Sat';
        $package->start_time = '08:00';
        $package->finish_time = '04:00';
        $package->min_price = 300;
        $package->max_price = 500;
        $package->hours_inc = 5;
        $package->overtime_cost = 25;
        $package->overtime_interval = 30;
        $package->free_travel = 0;
        $package->travel_cost = 50;
        $package->travel_interval = 60;
        $package->setup_time = 60;
        $package->email_template_id = 1;
        $package->save();
        
        $distance_in_minutes = PackageHelper::get_minutes_between_postcodes('TA63TJ', "EX20 3HB"); 
        
        # extra hours: 1
        $this->assertEquals(350, PackageHelper::get_disco_cost($package, 6));
        
        $this->assertEquals(50, PackageHelper::get_travel_cost($package, $distance_in_minutes));
        
        $this->assertEquals(400, PackageHelper::calculate_booking_cost($package->id, 6, $distance_in_minutes));
	}


}

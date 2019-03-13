<?php

use Helpers\PackageHelper;

class PostcodeTest extends TestCase {
    
    
	public function testDistance()
	{
        $diff = abs(8 - PackageHelper::get_miles_between_postcodes('TA63TJ','TA51nf'));
        $this->assertLessThanOrEqual(10, $diff);
	}
    
	public function testTime()
	{
        $diff = abs(15 - PackageHelper::get_minutes_between_postcodes('TA63TJ','TA51nf'));
        $this->assertLessThanOrEqual(3, $diff);
	}
    
	public function testLargeTime_1()
	{
        $diff = abs(37 - PackageHelper::get_minutes_between_postcodes('TA63TJ','BS229YX'));
        $this->assertLessThanOrEqual(4, $diff);
	}
    
	public function testLargeTime_2()
	{
        $diff = abs(165 - PackageHelper::get_minutes_between_postcodes('TA63TJ','EC4R9EL')); // the distance keeps changing!
        $this->assertLessThanOrEqual(20, $diff);
	}
    
    /** @test */
	public function postcode_not_found()
	{
        $diff = abs(28 - PackageHelper::get_minutes_between_postcodes('TA63TJ','BS247JU'));
        $this->assertLessThanOrEqual(5, $diff);        
	}


}

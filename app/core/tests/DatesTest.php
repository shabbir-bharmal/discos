<?php

use Helpers\DateTimeHelper;

class DatesTest extends TestCase
{    
    
    public function test_timepicker_to_dbtime()
    {        
        $this->assertEquals('20:20:00', DateTimeHelper::timepicker_to_dbtime('20:20'));
    }
    
    public function test_dbtime_to_timepicker()
    {        
        $this->assertEquals('20:20', DateTimeHelper::dbtime_to_timepicker('20:20:00'));
    }
    
    private function validate($time) 
    {   
        $input['start_time'] = $time;        
        
        \Validator::extend('time', function($attribute, $value, $parameters)
        {
            $parts = explode(':', $value);
            return (count($parts) == 2) && (is_numeric($parts[0]) && is_numeric($parts[1])) && (0 <= $parts[0] && $parts[0] < 24) && (0 <= $parts[1] && $parts[1] < 60);
        });
        
        $messages = array (
            'start_time.time' => 'The :attribute field time is invalid',
        );
        
        # validate request
        $validator = \Validator::make( $input,
            array('start_time' => 'time'),
                $messages);
        
        return $validator->passes();        
    }
    
    public function test_validateTime_1()
    {
        $this->assertTrue($this->validate('09:43'));
    }
    
    public function test_validateTime_2()
    {   
        $this->assertTrue($this->validate('19:00'));
    }
    
    public function test_validateTime_3()
    {   
        $this->assertTrue($this->validate('00:55'));
    }
    
    public function test_validateTime_4()
    {   
        $this->assertFalse($this->validate('24:00'));
    }
    
    public function test_validateTime_5()
    {   
        $this->assertFalse($this->validate('19:00:764'));
    }
    
    public function test_validateTime_6()
    {   
        $this->assertFalse($this->validate('d:fdf'));
    }
    
    public function test_14days_y()
    {
        $bookingdate_obj = new DateTime();
        $bookingdate_obj->add(new DateInterval('P14D'));
        
        $this->assertTrue($bookingdate_obj->sub(new DateInterval('P14D')) <= new DateTime());
    }
}

?>

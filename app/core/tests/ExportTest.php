<?php


class ExportTest extends TestCase
{
    use TestClientTrait;
    use TestBookingTrait;
    
    /** test */
    public function on_export_client_booking_and_package_details_are_given()
    {
        
        # configuration setup
        
        # seed data
        $client = $this->addClient();
        $package = Package::find(1);
        $booking = $this->addBooking($client, $package); 
        $fields = array(
            'client' => array('name'),
            'booking' => array('date'),
            'package' => array()
        );
        
        # do the action
        $all_bookings = Booking::all();
        
        $export = new Export($all_bookings, new DummyOutput());
        $export->run($fields);
        # test the result
    }
    
    /** test */
    public function on_filter_of_date()
    {        
        $client = $this->addClient();
        $package = Package::find(1);
        $booking = $this->addBooking($client, $package); 
        $postFilter = array(
            #'client' => array('name' => array('=' => 'client one')),
            'booking' => array('date' => array('=' => date('d-m-Y'))),
            'package' => array()
        );
        
        # do the action
        $filtered_bookings = Export::getCollectionFromPostFiltersForExport($postFilter);
        
        $this->assertCount(1, $filtered_bookings);
    }
    
    /** test */
    public function filter_on_venue_name()
    {
        $client = $this->addClient();
        $package = Package::find(1);
        $booking = $this->addBooking($client, $package); 
        $postFilter = array(
            'client' => array(),
            'booking' => array('venue_name' => array('=' => 'venue')),
            'package' => array()
        );
        
        $filtered_bookings = Export::getCollectionFromPostFiltersForExport($postFilter);
        
        $this->assertCount(1, $filtered_bookings);
    }
    
    /** test */
    public function filter_of_dates_after()
    {        
        $client = $this->addClient();
        $package = Package::find(1);
        $booking = $this->addBooking($client, $package); 
        $postFilter = array(
            #'client' => array('name' => array('=' => 'client one')),
            'booking' => array('date' => array('>' => '01-01-2000')),
            'package' => array()
        );
        
        # do the action
        $filtered_bookings = Export::getCollectionFromPostFiltersForExport($postFilter);
        
        $this->assertCount(1, $filtered_bookings);
    }
    
    /** @test */
    public function filter_of_dates_between()
    {        
        $client = $this->addClient();
        $package = Package::find(1);
        $booking = $this->addBooking($client, $package); 
        $postFilter = array(
            'client' => array(),
            'booking' => array('date' => array( '<' => '01-01-2020', '>' => '01-01-2000')),
            'package' => array()
        );
        
        # do the action
        $filtered_bookings = Export::getCollectionFromPostFiltersForExport($postFilter);
        
        $this->assertCount(1, $filtered_bookings);
    }
    
    /** @test */
    public function filter_of_range_of_ids()
    {        
        $client = $this->addClient();
        $package = Package::find(1);
        $booking = $this->addBooking($client, $package); 
        $postFilter = array(
            'client' => array(),
            'booking' => array(),
            'package' => array('id' => ['=' => '1,2,4-6'])
        );
        
        # do the action
        $filtered_bookings = Export::getCollectionFromPostFiltersForExport($postFilter);
        
        $this->assertCount(1, $filtered_bookings);
    }
    
    /** @test */
    public function filter_not_in_range_of_ids()
    {        
        $client = $this->addClient();
        $package = Package::find(3);
        $booking = $this->addBooking($client, $package); 
        $postFilter = array(
            'client' => array(),
            'booking' => array(),
            'package' => array('id' => ['=' => '1,2,4-6'])
        );
        
        # do the action
        $filtered_bookings = Export::getCollectionFromPostFiltersForExport($postFilter);
        
        $this->assertCount(0, $filtered_bookings);
    }
    
    /** @test */
    public function filter_of_range_of_invalid_ids_fail()
    {        
        $client = $this->addClient();
        $package = Package::find(1);
        $booking = $this->addBooking($client, $package); 
        $postFilter = array(
            'client' => array(),
            'booking' => array(),
            'package' => array('id' => ['=' => '1,2,8-6'])
        );
        
        $exception = false;
        
        # do the action
        try {
            $filtered_bookings = Export::getCollectionFromPostFiltersForExport($postFilter);
        } catch (Exception $e) {
            $exception = true;
        }
        
        $this->assertTrue($exception);
    }
    
    
}

?>

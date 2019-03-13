<?php

use Helpers\DateTimeHelper;
use Helpers\PackageHelper;

class Availability
{
    private $messages;
    private $debug;
    
    protected $date;
    protected $start_ts;
    protected $finish_ts;
    protected $package_name;
    protected $postcode;
    protected $name_comparison;
    
    public $package_id;
    public $set_id = 0;

    public $GAP_BETWEEN_GIGS = 2;
    public $NOT_AVAILABLE = 'Unfortunately this time is not available.';
    
    public static function make($data)
    {
        $availability = new Availability();
        $availability->date = $data['date'];
        
        $availability->start_ts = $data['start_timestamp'];
        $availability->finish_ts = $data['finish_timestamp'];
        $availability->package_name = $data['package_name'];
        $availability->postcode = $data['postcode'];
        $availability->debug = (isset($data['debug'])) ? $data['debug'] : false;
        $availability->name_comparison = (isset($data['name_comparison'])) ? $data['name_comparison'] : '=';
        
        $availability->messages = new \Illuminate\Support\MessageBag();
        
        return $availability;
    }
    
    public function is_free()
    {
        \Log::info("is free");
        if ($this->has_package()) {
            $sets = \Package::find($this->package_id)->equipmentSets;
            if ($sets) {
                foreach ($sets as $set) {
                    if ($this->is_free_from_other_bookings($set->id)) {
                        $this->set_id = $set->id;
                        return true;
                    }
                }
            } else {
                $this->messages->add('other', "others");
                return $this->is_free_from_other_bookings();
            }
        }else{
            //echo "no package"; die;
        }
        
        $this->messages->add('availability', $this->NOT_AVAILABLE);
        return false;
    }
    
    public function is_free_from_other_bookings($set_id = 0)
    {
        $bookings = \Booking::confirmed()->where('date', '=', $this->date->format('Y-m-d'))->where('set_id', '=', $set_id)->get();
        
        $package = \Package::find($this->package_id);
        \Log::info($package);
        foreach ($bookings as $already_booked) {
            $already_booked_date_obj = new \DateTime($already_booked->date);
            list($already_booked->start_ts, $already_booked->finish_ts) = DateTimeHelper::get_start_end_timestamps($already_booked_date_obj, $already_booked->start_time, $already_booked->finish_time);
            
            $distance_between_venues = PackageHelper::get_minutes_between_postcodes($this->postcode, $already_booked->venue_postcode);

            if ($distance_between_venues === false) {
                $this->messages->add('availability', 'Post code is invalid');
                return false;
            }
            
            $gap = $distance_between_venues + $already_booked->setup_equipment_time + $package->setup_time;
            $gap_interval = new DateInterval('PT'. $gap . 'M');
            
            $already_booked_date_obj->setTimestamp($already_booked->start_ts);
            $timestamp_of_arrival = $already_booked_date_obj->sub($gap_interval)->getTimestamp();
            $already_booked_date_obj->setTimestamp($already_booked->finish_ts);
            $timestamp_of_departure = $already_booked_date_obj->add($gap_interval)->getTimestamp();
            
            if ($this->finish_ts > $timestamp_of_arrival && $this->start_ts < $timestamp_of_departure) {
                $this->messages->add('availability', $this->NOT_AVAILABLE);
                return false;
            }
        }
     
        return true;
    }
    
    public function has_package()
    {
        \Log::info("has_package");
        \Log::info($this->package_name);
        //echo $this->package_name; die;
        $this->package_id = PackageHelper::get_package_id_from_request(
            $this->package_name,
            $this->date,
            date('H:i:s', $this->start_ts),
            date('H:i:s', $this->finish_ts),
            $this->debug,
            $this->name_comparison
        );
        
        return $this->package_id != false;
    }
    
    public function messages()
    {
        return $this->messages;
    }
}

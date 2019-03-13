<?php

use Helpers\PackageHelper;

class Costs
{
    private $messages;
    private $debug = false;
    
    protected $hq_postcode;
    protected $booking_obj;
    
    public static function make(\Booking $booking)
    {
        $cost = new Costs();
        
        $cost->hq_postcode = Setting::getValueFromKey('home_postcode');  
        $cost->booking_obj = $booking;        
        $cost->messages = new \Illuminate\Support\MessageBag();
        
        return $cost;
    }
    
    public function set_costs()
    {
        $distance = PackageHelper::get_minutes_between_postcodes($this->hq_postcode, $this->booking_obj->venue_postcode);
        if ($this->debug) echo "\n Distance between $this->hq_postcode and ".$this->booking_obj->venue_postcode.": $distance";
        $duration_in_hours = ($this->booking_obj->start_time < $this->booking_obj->finish_time) ? $this->booking_obj->finish_time - $this->booking_obj->start_time : '24:00:00' - $this->booking_obj->start_time + $this->booking_obj->finish_time;
        $this->booking_obj->total_cost = number_format(PackageHelper::calculate_booking_cost($this->booking_obj->package_id, $duration_in_hours, $distance, $this->debug), 2);
        
        $this->booking_obj->deposit_requested = $this->booking_obj->package->deposit;
        if ($this->debug) echo "\n Deposit: ".$this->booking_obj->deposit_requested;
        $this->booking_obj->deposit_paid = null;
        $this->booking_obj->deposit_amount = null;
        $this->booking_obj->deposit_payment_method = null;
        
        $this->booking_obj->balance_requested = $this->booking_obj->total_cost - $this->booking_obj->deposit_requested;
        $this->booking_obj->balance_paid = null;
        $this->booking_obj->balance_amount = null;
        $this->booking_obj->balance_payment_method = null;
        
        return $this->booking_obj;
    }
    
    public function messages()
    {
        return $this->messages;
    }
    
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }
}

?>

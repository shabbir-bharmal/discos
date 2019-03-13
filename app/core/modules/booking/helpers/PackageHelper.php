<?php

namespace Helpers;

use DB;

class PackageHelper
{    
    public static function get_package_id_from_request($name, \DateTime $date, $start_time, $finish_time, $debug = false, $name_comparison = '=')
    {
        //$debug = true;
        #\Log::info("getting package from request.  name: $name, date: ".$date->format('d-m-Y').", starttime: $start_time, finishtime: $finish_time, name_comparison: $name_comparison");
        if($debug) echo "name: $name, date: ".$date->format('d-m-Y').", starttime: $start_time, finishtime: $finish_time \n";
        
        // search rules first for special cases / exceptions
        $rules = \Rule::where('date_from', '<=', $date->format('Y-m-d'))
        ->where('date_to', '>=', $date->format('Y-m-d'))
        ->where('deleted', '=', '0')
        ->get();
        
        if(count($rules) > 0) {
            if($debug) echo "\n Package override rule found";
            return $rules[0]->package_id;
        }
        
        # get day of week based on date
        $day = $date->format('D');
        
        if ($finish_time < $start_time) {
            $finish_time = '23:59:59';
        }
        \Log::info($day);
        \Log::info($name_comparison);
        \Log::info($name);
        
        $packages = \Package::where('day', 'like', "%$day%")
        ->where('name', $name_comparison, $name)
        #->where('finish_time', '>', $start_time)
        ->orderby('start_time', 'desc')
        ->get();
        \Log::info("get_package");
        \Log::info($packages);
        if ($debug) {
            $query = DB::getQueryLog();
            var_dump(end($query));
            echo "Package count: ".count($packages) . "\n";
        }
        
        switch(count($packages)) {
            case 0:
                return false;
            #case 1:
            #    return $packages[0]->id; // don't return - need to check times are correct
            default:
                foreach($packages as $package) {
                
                    if ($debug){
                    echo "package starts: $package->valid_from - $package->valid_to - - $package->start_time, finish: $package->finish_time \n";

                    print_r(explode("/", $package->valid_from));

                        
                    } 
                
                    # account for finish_time
                    if($package->finish_time > $package->start_time && $package->finish_time <= $start_time) {
                        if ($debug) echo "Continuing \n";
                        continue;
                    }

                    if(!empty($package->valid_from)){
                        $valid_from = explode("-", $package->valid_from)[2].explode("-", $package->valid_from)[1].explode("-", $package->valid_from)[0];
                    }else{
                        $valid_from = "";
                    }

                    if(!empty($package->valid_to)){
                        $valid_to = explode("-", $package->valid_to)[2].explode("-", $package->valid_to)[1].explode("-", $package->valid_to)[0];
                    }else{
                        $valid_to = "";
                    }


                    /*echo "$package->start_time - $finish_time - $valid_from - $valid_to \n";
                    if($valid_from <= $date->format('Ymd') && (empty($valid_to) || $valid_to = "" || $valid_to = null || $valid_to >= $date->format('Ymd'))){
                        echo " Yes ";
                    }*/
                    if ($package->start_time < $finish_time && $valid_from <= $date->format('Ymd') && (empty($valid_to) || $valid_to = "" || $valid_to = null || $valid_to >= $date->format('Ymd'))) {
                        if ($debug) echo "Got package \n";
                        return $package->id;
                    }
                }
                if ($debug) echo "\n Not found package";
        }
        
        return false;        
    }
    
    public static function calculate_booking_cost($package_id, $event_duration_in_hours, $distance_in_minutes, $debug = false)
    {
        
        $package = \Package::find($package_id);
        
        if (!$package) {
            throw new Exception("Package not found, so quotation cannot be made");
        }
        
        $hours_cost = self::get_disco_cost($package, $event_duration_in_hours, $debug);        
        $travel_cost = self::get_travel_cost($package, $distance_in_minutes, $debug);
        
        if ($debug) echo "\n hours cost: $hours_cost, travel cost: $travel_cost";
        
        return $hours_cost + $travel_cost; // should this be: min($package->max_price, $hours_cost + $travel_cost) ?
    }
    
    public static function get_disco_cost(\Package $package, $event_duration_in_hours, $debug = false)
    {        
        $extra_hours = max($event_duration_in_hours - $package->hours_inc, 0);
        
        if($debug) echo "\n Duration in hours: $event_duration_in_hours";
        if($debug) echo "\n Extra hours: $extra_hours";
        if($debug) echo "\n Package min price: $package->min_price";
        
        return min($package->max_price, $package->min_price + (floor(($extra_hours * 60)/$package->overtime_interval) * $package->overtime_cost));
    }
    
    public static function get_travel_cost(\Package $package, $distance_in_minutes)
    {
        $extra_travel = max($distance_in_minutes - $package->free_travel, 0);  
        #echo "\n Extra travel: $extra_travel";
        return floor(($extra_travel / $package->travel_interval) * $package->travel_cost);
    }
    
    public static function get_miles_between_postcodes($origin_postcode, $destination_postcode)
    {        
        try {
            $api_url = "https://maps.googleapis.com/maps/api/distancematrix/json?";
            $params = array();

            $params['origins'] = $origin_postcode;
            $params['destinations'] = str_replace(' ', '', $destination_postcode);
            #$params['client'] = '';
            #$params['signature'] ='';
            //$params['key'] ='AIzaSyAjXG6fQDSW94PDGv01y3qCxPNADId-SvQ';
            $params['key'] =Setting::getValueFromKey('google_distance_matrix_api_key');
            $params['units'] = 'imperial';

            $results = json_decode(@file_get_contents($api_url . http_build_query($params)));
            
            #var_dump($results->rows[0]->elements[0]);exit;

            if($results->status == 'OK' && $results->rows[0]->elements[0]->status == 'OK') {
                $text = $results->rows[0]->elements[0]->distance->text;
                return trim(str_replace('mi', '', $text));
            } else if ($results->status == 'OK' && $results->rows[0]->elements[0]->status == 'NOT_FOUND') {
                return static::get_miles_between_postcodes($origin_postcode, substr($destination_postcode, 0, -1));
            }
        } catch (\Exception $e) {
            return false;
        }
        
        return false;
    }
    
    public static function get_minutes_between_postcodes($origin_postcode, $destination_postcode)
    {     
        try {
            $api_url = "https://maps.googleapis.com/maps/api/distancematrix/json?";
            $params = array();

            $params['origins'] = $origin_postcode;
            $params['destinations'] = str_replace(' ', '', $destination_postcode);
            #$params['client'] = '';
            #$params['signature'] ='';
            //$params['key'] ='AIzaSyAtHmnykc1pPjfrOwd0zNuLe-6Xs8Z-nDw';
            $params['key'] ='AIzaSyCi-R6q_8MlkGXXnQ5-OgTeiyLMheZmrpc';
            $params['units'] = 'imperial';

            $results = json_decode(@file_get_contents($api_url . http_build_query($params)));
            //print_r($results);
            #var_dump($results->rows[0]->elements[0]);exit;

            if($results->status == 'OK' && $results->rows[0]->elements[0]->status == 'OK') {
                $seconds = $results->rows[0]->elements[0]->duration->value;
                return intval($seconds / 60);
            } else if ($results->status == 'OK' && $results->rows[0]->elements[0]->status == 'NOT_FOUND') {
                return static::get_minutes_between_postcodes($origin_postcode, substr($destination_postcode, 0, -1));
            }
        } catch (\Exception $e) {
            return false;
        }
        
        return false;
    }

}
?>

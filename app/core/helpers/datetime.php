<?php

if (!function_exists('convert_time_to_parts')) {

    function convert_time_to_parts($timeStr)
    {
        $marker_pos = strpos($timeStr, ':');
        $hour = intval(substr($timeStr, 0, $marker_pos));
        if (strpos($timeStr, 'PM') > 0) {
            $hour += 12;
        }
        $min = intval(substr($timeStr, $marker_pos + 1, strpos($timeStr, ' ')));
        $sec = 0;

        #echo $hour, $min, $sec;        
        return array($hour, $min, $sec);
    }

}

if (!function_exists('dbtime_to_timepicker')) {

    function dbtime_to_timepicker($dbtime)
    {
        return substr($dbtime, 0, -3);
    }

}

if (!function_exists('timepicker_to_dbtime')) {

    function timepicker_to_dbtime($time_picker)
    {
        return $time_picker . ":00";
    }

}

if (!function_exists('timepicker_to_timestamp')) {

    function timepicker_to_timestamp($time_picker, DateTime $date)
    {
        # extract bits
        $parts = convert_time_to_parts($time_picker);

        # set time
        $date->setTime($parts[0], $parts[1], $parts[2]);

        # return time
        return $date->getTimestamp();
    }

}

if (!function_exists('timestamp_to_timepicker')) {

    function timestamp_to_timepicker($timestamp)
    {
        # format to time
        return date('h:i A', $timestamp);
    }

}

if (!function_exists('us_to_uk_date')) {

    function us_to_uk_date($date, $delimiter = '-')
    {
        $parts = explode($delimiter, $date);
        if (count($parts) < 3)
            return null;
        if ($parts[2] > 31) {
            return $date;
        }
        $date = $parts[2] . $delimiter . $parts[1] . $delimiter . $parts[0];
        return $date;
    }

}

if (!function_exists('uk_to_us_date')) {

    function uk_to_us_date(&$date, $delimiter = '-')
    {
        $parts = explode($delimiter, $date);
        if (count($parts) < 3)
            return null;
        if ($parts[0] > 31) {
            return $date;
        }
        $date = $parts[2] . $delimiter . $parts[1] . $delimiter . $parts[0];
        return $date;
    }

}

if (!function_exists('get_start_end_timestamps')) {

    function get_start_end_timestamps(\DateTime $dateObj, $start_time, $finish_time)
    {
        $parts = explode(':', $start_time);
        $dateObj->setTime($parts[0], $parts[1], $parts[2]);
        $start_timestamp = $dateObj->getTimestamp();
        $parts = explode(':', $finish_time);
        $dateObj->setTime($parts[0], $parts[1], $parts[2]);
        $finish_timestamp = $dateObj->getTimestamp();
        if ($finish_timestamp < $start_timestamp)
            $finish_timestamp = strtotime('+1 day', $finish_timestamp);
        
        return array($start_timestamp, $finish_timestamp);
    }

}
?>

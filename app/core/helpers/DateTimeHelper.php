<?php

namespace Helpers;

use Form;

class DateTimeHelper
{

    public static function convert_time_to_parts($timeStr)
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

    public static function dbtime_to_timepicker($dbtime)
    {
        return substr($dbtime, 0, -3);
    }

    public static function dbtime_to_hour($dbtime)
    {
        return substr($dbtime, 0, -6);
    }

    public static function timepicker_to_dbtime($time_picker)
    {
        return $time_picker . ":00";
    }

    public static function timepicker_to_timestamp($time_picker, \DateTime $date)
    {
        # extract bits
        $parts = self::convert_time_to_parts($time_picker);

        # set time
        $date->setTime($parts[0], $parts[1], $parts[2]);

        # return time
        return $date->getTimestamp();
    }

    public static function timestamp_to_timepicker($timestamp)
    {
        # format to time
        return date('h:i A', $timestamp);
    }

    public static function us_to_uk_date($date, $delimiter = '-')
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

    public static function uk_to_us_date($date, $delimiter = '-')
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

    public static function get_start_end_timestamps(\DateTime $dateObj, $start_time, $finish_time)
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

    public static function selectDays($name, $selected, $options = array())
    {
        $timestamp = strtotime('next Sunday');
        $days = array();
        for ($i = 0; $i < 7; $i++) {
            $day = strftime('%A', $timestamp);
            $days[$day] = $day;
            $timestamp = strtotime('+1 day', $timestamp);
        }

        return Form::select($name, $days, $selected, $options);
    }
}

?>

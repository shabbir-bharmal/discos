<?php

namespace Helpers;

use Exception;

class StringsHelper
{
    /*
     * Get a list of integers from a list/range eg. 1-4,7,8
     */
    public static function extractIntegers($string)
    {
        $integers = [];

        $parts = explode(",", $string);

        foreach ($parts as $part) {
            $trimmed = trim($part);
            if (strpos($trimmed, '-') === false) {
                $integers[] = $trimmed;
            } else {
                $range = explode("-", $trimmed);
                if (count($range) != 2 || $range[0] > $range[1]) {
                    throw new Exception("Trying to extract number from invalid range: $string");
                }

                for ($i = $range[0]; $i < $range[1] + 1; $i++) {
                    $integers[] = $i;
                }
            }
        }

        rsort($integers);

        return $integers;
    }


    /*
     * given a free-text name field, extract title
     */
    public static function title($name)
    {
        $titles = [
            'Ms',
            'Miss',
            'Mrs',
            'Mr',
            'Master',
            'Rev',
            'Reverend',
            'Fr',
            'Father',
            'Dr',
            'Doctor',
            'Atty',
            'Attorney',
            'Prof',
            'Professor',
            'Hon',
            'Honorable',
            'Pres',
            'President',
            'Gov',
            'Governor',
            'Coach',
            'Ofc',
            'Officer',
            'Msgr',
            'Monsignor',
            'Sr',
            'Sister',
            'Br',
            'Brother',
            'Supt',
            'Superintendent',
            'Rep',
            'Representative',
            'Sen',
            'Senator',
            'Amb',
            'Ambassador',
            'Treas',
            'Treasurer',
            'Sec',
            'Secretary',
            'Pvt',
            'Private',
            'Cpl',
            'Corporal',
            'Sgt',
            'Sargent',
            'Adm',
            'Administrative',
            'Maj',
            'Major',
            'Capt',
            'Captain',
            'Cmdr',
            'Commander',
            'Lt',
            'Lieutenant',
            'Lt Col',
            'Lieutenant Colonel',
            'Col',
            'Colonel',
            'Gen',
            'General',
        ];

        foreach($titles as $title) {

            if (strpos($name, "$title ") !== false) return $title;
            if (strpos($name, "$title. ") !== false) return $title;
        }

        return '';
    }

    /*
     * given a free-text name field, extract first name
     */
    public static function firstName($name)
    {
        $title = self::title($name);
        $name_without_title = str_replace("$title. ", " ", $name);
        $name_without_title = str_replace("$title ", " ", $name_without_title);

        $words = explode(" ", $name_without_title);

        if ($words[0] == '') array_shift($words);

        return count($words) > 0 ? array_shift($words) : '';
    }

    /*
     * given a free-text name field, extract surname
     */

    public static function lastName($name)
    {
        $words = explode(" ", $name);
        return count($words) > 1 ? array_pop($words) : '';
    }
}

?>

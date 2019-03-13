<?php

use Carbon\Carbon;

class TimeSlots {

    const RANGE_IN_WEEKS = 2;

    public static $times = [
        ['start'=>11, 'end'=>13],
        ['start'=>15, 'end'=>17],
        ['start'=>19, 'end'=>21],
        #['start'=>17, 'end'=>19],
        #['start'=>18, 'end'=>20],
        #['start'=>19, 'end'=>21],
    ];

    public static function validate($date, $postcode)
    {
        $rules = [
            'date' => 'required|date|after:now',
            'postcode' => 'required|postcode'
        ];

        $validator = Validator::make( ['date' => $date, 'postcode' => $postcode], $rules );

        if($validator->fails()) {
            return $validator->messages();
        }

        return true;
    }

    public static function get_slots_to_check($date)
    {
        $date = Carbon::createFromFormat('d-m-Y', $date);

        $start = Carbon::createFromTimestamp($date->subWeeks(self::RANGE_IN_WEEKS)->timestamp);
        $start = $start->lte(Carbon::today()->addDay()) ? Carbon::today()->addDay() : $start;
        $end = Carbon::createFromTimestamp($date->addWeeks(2 * self::RANGE_IN_WEEKS)->timestamp);

        $slots_to_check = [];

        while($start->lt($end)) {

            foreach(self::$times as $time) {

                $slot['date'] = $start->copy();
                $start->setTime($time['start'], 0, 0);
                $slot['start_timestamp'] = $start->timestamp;
                $start->setTime($time['end'], 0, 0);
                $slot['finish_timestamp'] = $start->timestamp;

                $slots_to_check[] = $slot;
            }

            $start->addDay();
        }

        return $slots_to_check;
    }

}
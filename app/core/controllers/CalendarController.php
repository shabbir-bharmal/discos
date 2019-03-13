<?php

/*
 * For iCalendar
 */
class CalendarController extends BaseController
{
    public function export()
    {
        $this->writeLog();
        $this->data['bookings'] = Booking::confirmed()->get();
        $this->data['unavailable_dates'] = Rule::where('deleted', '=', 0)->get();
        return LResponse::view('calendar/ical', $this->data)->header('Content-type', 'text/calendar; charset=utf-8')->header('Content-Disposition', 'inline; filename=calendar.ics');
    }
    
    private function writeLog()
    {
        $myFile = "result.txt";
        $fh = fopen($myFile, 'a');
        $stringData = "";
        $stringData .= "Start : " . date("Y-m-d H:i:s") . " : " . $_SERVER["REMOTE_ADDR"] . "\\n";
        $stringData .= "-------------------------------\n";
        fwrite($fh, $stringData);
        fclose($fh);
    }
}

?>

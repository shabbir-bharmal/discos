<?php

function writeLog()
{
    $myFile = "result.txt";
    $fh = fopen($myFile, 'a');
    $stringData = "";
    $stringData .= "Start : " . date("Y-m-d H:i:s") . " : " . $_SERVER["REMOTE_ADDR"] . "\\n";
    $stringData .= "-------------------------------\n";
    fwrite($fh, $stringData);
    fclose($fh);
}

$eol = "\r\n";

writeLog();
//set correct content-type-header
header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: inline; filename=calendar.ics');

$content =
        "BEGIN:VCALENDAR" . $eol .
        "VERSION:2.0" . $eol .
        "PRODID:-//hacksw/handcal//NONSGML 1.0//EN" . $eol .
        "CALSCALE:GREGORIAN" . $eol .
        "METHOD:PUBLISH" . $eol .
        "X-WR-CALNAME:DJ Nick Burrett Calendar" . $eol .
        "X-WR-TIMEZONE:Europe/London" . $eol .
        "X-WR-CALDESC:Calendar showing future 12 months events " . $eol;

$con = mysqli_connect("localhost", "discosuk_jopph", "pw_3$3KbiQoN", "discosuk_jopph");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$bookings = mysqli_query($con, "SELECT *, c.name as client_name, b.id as booking_id 
    FROM bookings b 
    JOIN clients c ON b.client_id = c.id 
    WHERE b.status = 'booking' and b.deleted = 0");

if($bookings) {

    while ($booking = mysqli_fetch_array($bookings)) {
        
        $end_date = date('Ymd', strtotime($booking['date'] . " " . $booking['finish_time']));
        if ($booking['finish_time'] < $booking['start_time']) $end_date = date('Ymd', strtotime('+1 day', strtotime($booking['date'])));
        $staff = ($booking['staff'] ?: "Staff") . ' - ';
        
        
        $content .= "BEGIN:VEVENT" . $eol;
        $content .= "UID:" . $booking['booking_id'] . "@theweddingdj.co.uk  " . $eol;
        $content .= "DTSTART:" . date('Ymd', strtotime($booking['date'] . " " . $booking['start_time'])) . "T" . date('His', strtotime($booking['date'] . " " . $booking['start_time'])) . $eol;
        $content .= "DTEND:" . date('Ymd', strtotime($end_date . " " . $booking['finish_time'])) . "T" . date('His', strtotime($end_date . " " . $booking['finish_time'])) . $eol;
        $content .= "SUMMARY:" . $staff . addcslashes($booking['client_name'], ",") . " - " . $booking['event_occasion'] . "  " . $eol;
        $content .= "LOCATION:" . addcslashes($booking['venue_name'], ',') . "\, " . addcslashes($booking['venue_address1'], ',') . "\, " . $booking['venue_address2'] . "\, " . $booking['venue_address3'] . "\, " . $booking['venue_postcode'] . "  " . $eol;
        $content .= "DESCRIPTION:Contact Telephone: " . $booking['telephone'] . "\\nContact Mobile: " . $booking['mobile'] . "\\nContact Email: " . $booking['email'] . "\\nStaff Required: " . $booking['staff'] . "\\n\\nBrides Name: " . $booking['bride_firstname'] . "\\nGrooms Name: " . $booking['groom_firstname'] . "\\nBride and Groom Surname: " . $booking['groom_surname'] . "\\n\\nBirthday Persons Name: " . addcslashes($booking['birthday_name'], ",") . "\\nBirthday Persons Age: " . addcslashes($booking['birthday_age'], ",") . "\\n\\nhttp://www.discos.uk/admin/\\n\\nPackage Cost: " . $booking['total_cost'] . "\\n\\nDeposit Paid: " . $booking['deposit_amount'] . "\\nDeposit Paid Date: " . $booking['deposit_paid'] . "\\nDeposit Amount Requested: " . $booking['deposit_requested'] . "\\n\\nBalance Paid: " . $booking['balance_amount'] . "\\nBalance Paid Date: " . $booking['balance_paid'] . "\\nBalance Paid Method: " . $booking['balance_payment_method'] . "  " . $eol;
        $content .= "BEGIN:VALARM" . $eol;
        $content .= "TRIGGER:-PT2H" . $eol;
        $content .= "ACTION:DISPLAY" . $eol;
        $content .= "DESCRIPTION:Reminder" . $eol;
        $content .= "END:VALARM" . $eol;
        $content .= "BEGIN:VALARM" . $eol;
        $content .= "TRIGGER:-P1D" . $eol;
        $content .= "ACTION:DISPLAY" . $eol;
        $content .= "DESCRIPTION:Reminder" . $eol;
        $content .= "END:VALARM" . $eol;
        $content .= "BEGIN:VALARM" . $eol;
        $content .= "TRIGGER:-P2D" . $eol;
        $content .= "ACTION:DISPLAY" . $eol;
        $content .= "DESCRIPTION:Reminder" . $eol;
        $content .= "END:VALARM" . $eol;
        $content .= "END:VEVENT" . $eol;
    }
}

// unavailable dates
$unavailables = mysqli_query($con, "SELECT * 
    FROM rules
    WHERE deleted = 0");

if($unavailables) {

    while ($unavailable = mysqli_fetch_object($unavailables)) {
        
        $date = new DateTime($unavailable->date_from);
        $final_date = new DateTime($unavailable->date_to);
        
        while ($date <= $final_date ) {


            $content .= "BEGIN:VEVENT" . $eol;
            $content .= "UID:" . $unavailable->id . $date->format('Ymd') . "@theweddingdj.co.uk  " . $eol;
            $content .= "DTSTART;VALUE=DATE:" . $date->format('Ymd') . $eol;
            $content .= "SUMMARY:" . $unavailable->name . $eol;
            #$content .= "LOCATION:" . $eol;
            $content .= "DESCRIPTION:" . "Unavailable" . $eol;
            $content .= "END:VEVENT" . $eol;
            
            
            $date->add(new DateInterval('P1D'));
        }
    }
}


mysqli_close($con);

$content .= "END:VCALENDAR";
echo $content;
exit;
?>
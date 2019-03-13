BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML 1.0//EN
CALSCALE:GREGORIAN
METHOD:PUBLISH
X-WR-CALNAME:DJ Nick Burrett Calendar
X-WR-TIMEZONE:Europe/London
X-WR-CALDESC:Calendar showing future 12 months events 
@foreach ($bookings as $booking)
<?php $end_date = date('Ymd', strtotime($booking->date . " " . $booking->finish_time));if ($booking->finish_time < $booking->start_time) $end_date = date('Ymd', strtotime('+1 day', strtotime($booking->date)));$staff = ($booking->staff ?: "Staff") . ' - ';?>
BEGIN:VEVENT
UID:{{$booking->id}}@theweddingdj.co.uk  
DTSTART:{{date('Ymd', strtotime($booking->date . " " . $booking->start_time))}}T{{date('His', strtotime($booking->date . " " . $booking->start_time))."\r\n"}}
DTEND:{{date('Ymd', strtotime($end_date . " " . $booking->finish_time))}}T{{date('His', strtotime($end_date . " " . $booking->finish_time))."\r\n"}}
SUMMARY:{{$staff . addcslashes($booking->client['name'], ",")}} - {{$booking->event_occasion . " "}} 
LOCATION:{{addcslashes($booking->venue_name, ',')}}\, {{addcslashes($booking->venue_address1, ',')}}\, {{$booking->venue_address2}}\, {{$booking->venue_address3}}\, {{$booking->venue_postcode." "}} 
DESCRIPTION:Contact Telephone: {{$booking->client['telephone']}}\nContact Mobile: {{$booking->client['mobile']}}\nContact Email: {{$booking->client['email']}}\nStaff Required: {{$booking->staff}}\n\nBrides Name: {{$booking->bride_firstname}}\nGrooms Name: {{$booking->groom_firstname}}\nBride and Groom Surname: {{$booking->groom_surname}}\n\nBirthday Persons Name: {{addcslashes($booking->birthday_name, ",")}}\nBirthday Persons Age: {{addcslashes($booking->birthday_age, ",")}}\n\nhttp://www.discos.uk/admin/\n\nPackage Cost: {{$booking->total_cost}}\n\nDeposit Paid: {{$booking->deposit_amount}}\nDeposit Paid Date: {{$booking->deposit_paid_us}}\nDeposit Amount Requested: {{$booking->deposit_requested}}\n\nBalance Paid: {{$booking->balance_amount}}\nBalance Paid Date: {{$booking->balance_paid_us}}\nBalance Paid Method: {{$booking->balance_payment_method}}\nNotes: {{$booking->notes_wrapped}}  
BEGIN:VALARM
TRIGGER:-PT2H
ACTION:DISPLAY
DESCRIPTION:Reminder
END:VALARM
BEGIN:VALARM
TRIGGER:-P1D
ACTION:DISPLAY
DESCRIPTION:Reminder
END:VALARM
BEGIN:VALARM
TRIGGER:-P2D
ACTION:DISPLAY
DESCRIPTION:Reminder
END:VALARM
END:VEVENT
@endforeach
@foreach ($unavailable_dates as $unavailable)
<?php $date = new DateTime($unavailable->date_from);$final_date = new DateTime($unavailable->date_to); ?>
@while ($date <= $final_date)
BEGIN:VEVENT
UID:{{$unavailable->id . $date->format('Ymd')}}@theweddingdj.co.uk  {{"\r\n"}}
DTSTART;VALUE=DATE:{{$date->format('Ymd')."\r\n"}}
SUMMARY:{{$unavailable->name."\r\n"}}
DESCRIPTION:Unavailable{{"\r\n"}}
END:VEVENT
<?php $date->add(new DateInterval('P1D'));?>
@endwhile
@endforeach
END:VCALENDAR
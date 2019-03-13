@if( $data->bride_firstname != '' && $data->groom_firstname != '')
Dear {{ $data->bride_firstname }} and {{ $data->groom_firstname }}
@elseif( Helpers\StringsHelper::title($data->client->name) != '')
Dear {{ $data->client->name }}
@else
Dear {{ Helpers\StringsHelper::firstName($data->client->name) }}
@endif<br><br>

Thank you for booking your up and coming event with Smartfone Quiz. Your booking is now confirmed.<br><br>

My name is Nick and I will be your quizmaster on {{date("l jS \of F Y",strtotime($data->date))}}. Should you wish to contact me at any stage to discuss your event, my details are at the end of this email.<br><br>

--- Event Details ---<br><br>

Date of Event: {{date("l jS \of F Y",strtotime($data->date))}}<br>
Event Type: {{$data->occasion}}<br>
Start Time: {{substr($data->start_time, 0, -3)}}<br>
End Time: {{substr($data->finish_time, 0, -3)}}<br>
Quizmaster Arrival Time: {{"";  $timestamp = $data->start_timestamp; $timestamp -= (60 * $data->setup_equipment_time); echo strftime("%H:%M", $timestamp)  }}<br><br>

Venue:
<div>{{$data->venue_name}}</div>
<div>{{$data->venue_address1}}</div>
<div>{{$data->venue_address2}}</div>
<div>{{$data->venue_address3}}</div>
<div>{{$data->venue_postcode}}</div><br>

Total Cost: £{{$data->total_cost}}<br>
Deposit Required: £{{$data->deposit_requested}}<br>
Deposit Paid: £{{$data->deposit_amount}}<br>
Balance Required: £{{$data->balance_requested}}<br>
Balance Paid: £{{$data->balance_amount}}<br><br><br>


--- When will you next hear from me? ---<br><br>

I generally contact you again approximately 1 week before your event to finalise details with you. However, if you have any questions, concerns, or just want a general chat about your event, feel free to contact me at any time.<br><br>

Thanks again for choosing Smartfone Quiz to provide the quizmaster for your event. I look forward to seeing you on {{date("l jS \of F Y",strtotime($data->date))}}.<br><br>

Kind Regards<br>
Nick Burrett<br>
<a href="https://www.smartfonequiz.co.uk">www.smartfonequiz.co.uk</a><br>
01934 444666
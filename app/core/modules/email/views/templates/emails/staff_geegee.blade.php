Georgia (Colette)<br><br>

Please find a list of future bookings that you are required to attend, please let me know if any of the dates of unsuitable for you.<br><br>

Kind Regards<br>
Nick<br>
(Automated Message)<br><br>

<hr><br>


@foreach ($data['future_bookings'] as $future_booking)

@if( $future_booking->staff === 'Nick + GeeGee')

<div>{{ date("D d-m-Y",strtotime($future_booking->date)) }}</div>

<div>Collection Time (approx): 
{{"";  $timestamp = $future_booking->start_timestamp;
$timestamp -= (60 * $future_booking->setup_equipment_time);
$timestamp -= (60 * $future_booking->travel_time);
$timestamp -= (60 * 15); 
echo strftime("%H:%M", $timestamp)  }}</div>

<div>Drop Off Time (approx): 
{{"";  $timestamp = $future_booking->finish_timestamp;
$timestamp += (60 * $future_booking->travel_time);
$timestamp += (60 * 15); 
echo strftime("%H:%M", $timestamp)  }}</div>

<br>=====<br>

@else

@endif

@endforeach

<br/><br/>
Job run at {{date('H:i:s, d-m-Y')}}
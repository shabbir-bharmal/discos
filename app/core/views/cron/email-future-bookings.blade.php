<p>Hi,</p>

<p>You have the following future bookings: </p>

@foreach ($future_bookings as $future_booking)

<p>{{$future_booking->client->name}}  ({{$future_booking->package->name}})<br/>
{{$future_booking->date}}, {{substr($future_booking->start_time, 0, -3)}} - {{substr($future_booking->finish_time, 0, -3)}}<br/>
{{$future_booking->venue_name}}, {{$future_booking->venue_address1}}, {{$future_booking->venue_address2}}, {{$future_booking->venue_address3}}, {{$future_booking->venue_address4}}, {{$future_booking->venue_postcode}}<br/>
{{$future_booking->staff}}</p>

@endforeach

<br/><br/>
CRON job run at {{date('H:i:s, d-m-Y')}}
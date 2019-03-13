@foreach ($data['future_bookings'] as $future_booking)

<div>{{ date("D d-m-Y",strtotime($future_booking->date)) }} >>> {{substr($future_booking->start_time, 0, -3)}} until {{substr($future_booking->finish_time, 0, -3)}} (Staff: {{$future_booking->staff}})</div>

<div>{{$future_booking->venue_name}}</div>
<div>{{$future_booking->venue_address1}}</div>
<div>{{$future_booking->venue_address2}}</div>
<div>{{$future_booking->venue_address3}}</div>
<div>{{$future_booking->venue_address4}}</div>
<div>{{$future_booking->venue_postcode}}</div>

<br>

<div>Client: <?php echo (isset($future_booking->client) && !empty($future_booking->client))?$future_booking->client->name:""; ?></div>

@if(!empty($future_booking->client->telephone))
<div>Telephone: {{$future_booking->client->telephone}}</div>
@endif

@if(!empty($future_booking->client->mobile))
<div>Mobile: {{$future_booking->client->mobile}}</div>
@endif

@if(!empty($future_booking->client->email))
<div><strong>Email: {{$future_booking->client->email}}</strong></div>
@endif

@if(!empty($future_booking->occasion))
<div>Occasion: {{$future_booking->occasion}}</div>
@endif

@if(!empty($future_booking->package->name))
<div>Package: {{$future_booking->package->name}}</div>
@endif

@if(!empty($future_booking->birthday_name))
<div>Guests Of Honour: {{$future_booking->birthday_name}}</div>
@endif

@if(!empty($future_booking->birthday_age))
<div>Ages: {{$future_booking->birthday_age}}</div>
@endif

@if(!empty($future_booking->bride_firstname) || !empty($future_booking->groom_firstname) || !empty($future_booking->groom_surname))
<div>Bride and Groom: {{$future_booking->bride_firstname}} & {{$future_booking->groom_firstname}} {{$future_booking->groom_surname}}</div>
@endif

<br>

<div>Leave Bridgwater: 
{{"";  $timestamp = $future_booking->start_timestamp;
$timestamp -= (60 * $future_booking->setup_equipment_time);
$timestamp -= (60 * $future_booking->travel_time);
$timestamp -= (60 * 15); 
echo strftime("%H:%M", $timestamp)  }}</div>

<div>Arrive Venue: 
{{"";  $timestamp = $future_booking->start_timestamp;
$timestamp -= (60 * $future_booking->setup_equipment_time);
$timestamp -= (60 * 15);
echo strftime("%H:%M", $timestamp)  }}</div>

<div>Arrive Bridgwater: 
{{"";  $timestamp = $future_booking->finish_timestamp;
$timestamp += (60 * $future_booking->travel_time);
$timestamp += (60 * 15); 
echo strftime("%H:%M", $timestamp)  }}</div>

<br>

<div>Balance Due: {{$future_booking->balance_requested}}</div>
<div>Balance Paid: {{$future_booking->balance_amount}}</div>

<br>

@if(!empty($future_booking->notes))
<div>Notes: {{$future_booking->notes}}</div>
@endif

<br><hr><br>

@endforeach

<br/><br/>
Job run at {{date('H:i:s, d-m-Y')}}
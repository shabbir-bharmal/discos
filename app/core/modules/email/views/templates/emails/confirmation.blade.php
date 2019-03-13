The following customer has already received a quotation, do you wish to send another?<br><br>

<strong>Event date:</strong> {{ $booking->date }}<br>
<strong>Start Time:</strong> {{ $booking->start_time }}<br>
<strong>Finish Time:</strong> {{ $booking->finish_time }}<br>
<strong>Venue Name:</strong> {{ $booking->venue_name }}<br>
<strong>Venue Postcode:</strong> {{ $booking->venue_postcode }}<br>
<strong>Package Id:</strong> {{ $booking->package_id }}<br>
<strong>Setup Equipment Time:</strong> {{ $booking->setup_equipment_time }}<br>
<strong>Client name:</strong> {{ $booking->client->name }}<br>
<strong>Client Email:</strong> {{ $booking->client->email }}<br>
<strong>Client Telephone:</strong> {{ $booking->client->telephone }}<br>
<strong>Client Mobile:</strong> {{ $booking->client->mobile }}<br>
<strong>Heard About:</strong> {{ $booking->client->heard_about }}<br>
<strong>Ref No:</strong> {{ $booking->ref_no }}<br>
<br>
<a href="{{ route('confirm-booking', $booking->id) }}">Send again?</a> or do nothing to discard.
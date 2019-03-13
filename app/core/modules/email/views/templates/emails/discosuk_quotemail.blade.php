Dear @if( Helpers\StringsHelper::title($client->name) != '')
{{ $client->name }}
@else
{{ studly_case(Helpers\StringsHelper::firstName($client->name)) }}
@endif<br><br>

Thank you for contacting me regarding your party on {{date("l jS \of F Y",$booking["timestamp"])}}.  I have pleasure in confirming my availability and providing you with a quotation for your event.<br><br>

To provide a disco between {{$booking["start_time_formatted"]}} and {{$booking["finish_time_formatted"]}} at {{$booking["venue_name"]}}, {{$booking["venue_postcode"]}} would be £{{$booking["total_cost"]}}.<br><br>

This above quotation includes:<br>
5 Star Rated DJ (myself - DJ Nick Burrett)<br>
An appropriate sound system for your venue<br>
Disco lighting<br>
Requests welcome in advance and on the night<br>
Public liability insurance<br>
PAT tested equipment<br><br>

Additional services include:<br>
Karaoke: Free<br>
Children's Entertainment: Free<br>
Fingers on Buzzers Quiz: £50<br>
Musical Bingo: £50<br><br>

To place a booking and secure the date, simply <a href="https://www.discos.uk/booking/{{ $booking->email_token }}">click here</a>.<br><br>

If you have any questions about the service I provide, please do not hesitate to ask.  Alternatively you can browse my website at <a href="https://www.discos.co.uk">www.discos.co.uk</a> or check out my testimonials from previous customers via the <a href="https://www.buywithconfidence.gov.uk/profile/dj-nick-burrett/13389/">Buy With Confidence website</a>.<br><br>

I look forward to hearing from you.<br><br>

Kind Regards<br>
Nick Burrett<br>
<a href="https://www.discos.co.uk">www.discos.co.uk</a><br>
01823 240300
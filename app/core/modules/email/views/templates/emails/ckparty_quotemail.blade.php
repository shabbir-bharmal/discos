Dear @if( Helpers\StringsHelper::title($client->name) != '')
{{ $client->name }}
@else
{{ studly_case(Helpers\StringsHelper::firstName($client->name)) }}
@endif<br><br>

Thank you for contacting me regarding your party on {{date("l jS \of F Y",$booking["timestamp"])}}.  I have pleasure in confirming my availability and providing you with a quotation for your event.<br><br>

Our 11am - 1pm slot is £{{$booking["total_travel_cost"] + 130}}.<br>
Our 3pm - 5pm slot is £{{$booking["total_travel_cost"] + 150}}.<br>
Our 7pm - 9pm slot is £{{$booking["total_travel_cost"] + 130}}.<br><br>

You can book a party instantly online with just a £10 deposit via our website booking system at:
<a href="https://www.coolkidsparty.com/online-booking/">https://www.coolkidsparty.com/online-booking/</a>.<br><br>

Prices quoted include the music, sound equipment, disco lights and myself as the children’s entertainer to host party games and give out prizes (included free of charge).  Games are chosen on the day once I get to know how the children are likely to get on with them, but could include limbo, relay games, mummy wrapping etc.  As a general rule we alternate between games and party dances throughout the party, plus a break for food in the middle.<br><br>

You can see some videos from our previous parties here:<br>
<a href="https://youtu.be/yNEHyOhB0fs">https://youtu.be/yNEHyOhB0fs</a><br>
<a href="https://youtu.be/a0CNOvHVK9I">https://youtu.be/a0CNOvHVK9I</a><br><br>

I look forward to hearing from you.<br><br>

Kind Regards<br>
Nick Burrett<br>
<a href="https://www.coolkidsparty.com">www.coolkidsparty.com</a><br>
01278 393100
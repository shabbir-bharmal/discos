Please could you contact the customer below and confirm they have received our quotation:<br><br>

<div>{{ $data->client->name }}</div>
<div>{{ $data->client->email }}</div>
<div>{{ $data->client->telephone }}</div>
<div>{{ $data->client->mobile }}</div>

===== ===== ===== =====<br><br>

Dear @if( Helpers\StringsHelper::title($data->client->name) != '')
{{ $data->client->name }}
@else
{{ studly_case(Helpers\StringsHelper::firstName($data->client->name)) }}
@endif<br><br>

Thank you for contacting me regarding your event on {{date("l jS \of F Y",strtotime($data->booking["date"]))}}.  I have pleasure in confirming my availability and providing you with a quotation.<br><br>

To provide a Smartfone Quiz between {{$data->booking["start_time_formatted"]}} and {{$data->booking["finish_time_formatted"]}} at {{$data->booking["venue_name"]}}, {{$data->booking["venue_postcode"]}} would be £{{$data->booking["total_cost"]}}.<br><br>

So, what on earth is a Smartfone Quiz?<br><br>

It's a general knowledge pub quiz played using a free App downloaded to your smartphone or tablet.<br><br>

It's easy to play:<br>
For multiple choice questions just tap ABCDE or F.<br>
For numbers, tap the number and press Enter.<br>
And for letters, if you think the answer is PARIS, just press P!<br>
You don't even have to type in the full answer, just one tap on your screen gets you the points (or not as the case may be!).<br>

You get to choose your own team buzzer sound, played whenever you get the answer right first, and there's bonus points on a sliding scale for the fastest teams.<br><br>

If you have any questions about this evil smartphone technology that's worlds away from old fashioned paper and pen quizzes, just let me know as I'd love to convert you to smartphone quizzing!<br><br>

Oh, I nearly forgot - It's impossible to cheat!!!  So you can't Google the answers in the loo or under the table ;-)<br><br>

I look forward to hearing from you.<br><br>

Kind Regards<br>
Nick Burrett<br>
<a href="https://www.smartfonequiz.co.uk">www.smartfonequiz.co.uk</a><br>
01934 444666
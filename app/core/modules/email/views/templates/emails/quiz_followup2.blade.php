Dear @if( Helpers\StringsHelper::title($client->name) != '')
{{ $client->name }}
@else
{{ studly_case(Helpers\StringsHelper::firstName($client->name)) }}
@endif<br><br>

I hope you don't mind me sending another email to see how you are progressing with your plans to hire a quizmaster?  Is there anything I can do to help speed the process along?<br><br>

If you are ready to place a booking and secure the date, simply <a href="{{ config('app.url') . 'booking/' . $booking['email_token'] }}">click here</a>.<br><br>

I look forward to hearing from you.<br><br>

Kind Regards<br>
Nick Burrett<br>
<a href="https://www.smartfonequiz.co.uk">www.smartfonequiz.co.uk</a><br>
01934 444666<br><br>

===== ===== ===== ===== =====<br><br>


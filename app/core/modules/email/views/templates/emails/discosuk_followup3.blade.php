Dear @if( Helpers\StringsHelper::title($data->client->name) != '')
{{ $data->client->name }}
@else
{{ studly_case(Helpers\StringsHelper::firstName($data->client->name)) }}
@endif<br><br>

This will be my last email to see if there's anything I can do to sway you into booking my services.  So, this is just a courtesy message to say please get in touch if I can be of any assistance.  If not, then I wish you all the best for your event :-)<br><br>

If you are ready to place a booking and secure the date, simply <a href="{{ Config::get('app.url') . 'booking/' . $data->booking['email_token'] }}">click here</a>.<br><br>

I look forward to hearing from you.<br><br>

Kind Regards<br>
Nick Burrett<br>
<a href="https://www.discos.co.uk">www.discos.co.uk</a><br>
01823 240300<br><br>

===== ===== ===== ===== =====<br><br>


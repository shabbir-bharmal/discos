Dear @if( Helpers\StringsHelper::title($data->client->name) != '')
{{ $data->client->name }}
@else
{{ studly_case(Helpers\StringsHelper::firstName($data->client->name)) }}
@endif<br><br>

I'm just following up the enquiry you recently made about hiring a wedding DJ.  If there's anything I haven't covered or there's something you'd like me to answer, please don't hesitate to get in touch.<br><br>

If you are ready to place a booking and secure the date, simply <a href="{{ Config::get('app.url') . 'booking/' . $data->booking['email_token'] }}">click here</a>.<br><br>

I look forward to hearing from you.<br><br>

Kind Regards<br>
Nick Burrett<br>
<a href="https://www.discos.co.uk">www.discos.co.uk</a><br>
01823 240300<br><br>

===== ===== ===== ===== =====<br><br>


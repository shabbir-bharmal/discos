@if( $data->bride_firstname != '' && $data->groom_firstname != '')
Dear {{ $data->bride_firstname }} and {{ $data->groom_firstname }}
@elseif( Helpers\StringsHelper::title($data->client->name) != '')
Dear {{ $data->client->name }}
@else
Dear {{ Helpers\StringsHelper::firstName($data->client->name) }}
@endif
<br/><br/>

As you may recall we provided your disco on {{date("l jS \of F Y",strtotime($data->date))}}.<br/><br/>

I just wanted to send you a quick courtesy email to find out if you would like to book us again this year for any forthcoming parties?<br/><br/>

Please do not hesitate to reply to this email if I can be of any assistance.<br/><br/>

Kind Regards<br><strong>Nick Burrett</strong><br>Proprietor<br><a href="http://www.coolkidsparty.com">Cool Kids Party</a><br>01278 393100<br/><br/>
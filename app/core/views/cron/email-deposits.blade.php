<p>Hi,</p>

<p>The following bookings still have deposits outstanding: </p>

@foreach ($unpaid_deposits as $unpaid_deposit)

<p>{{$unpaid_deposit->client->name}} ({{$unpaid_deposit->package->name}}) {{$unpaid_deposit->date}}<br/>
Deposit due: &pound;{{$unpaid_deposit->deposit_requested}}, Deposit paid: &pound;{{$unpaid_deposit->deposit_amount}}</p>

@endforeach

<br/><br/>

<p>The following bookings are in the next 21 days and still have balances outstanding: </p>

@foreach ($unpaid_balances_coming_up as $unpaid_balance)

<p>{{$unpaid_balance->client->name}} ({{$unpaid_balance->package->name}})  {{$unpaid_balance->date}}<br/>
Balance due: &pound;{{$unpaid_balance->balance_requested}}, Balance paid: &pound;{{$unpaid_balance->balance_amount}}</p>

@endforeach

<br/><br/>
CRON job run at {{date('H:i:s, d-m-Y')}}
The following bookings still have deposits outstanding:<br/><br/>

@foreach ($data['unpaid_deposits'] as $unpaid_deposit)
{{$unpaid_deposit->client->name}} ({{$unpaid_deposit->package->name}}) {{$unpaid_deposit->date}}<br/>
Deposit due: £{{$unpaid_deposit->deposit_requested}}, Deposit paid: £{{$unpaid_deposit->deposit_amount}}<br/><br/>
@endforeach

<br/><br/>

The following bookings are in the next 21 days and still have balances outstanding:<br/><br/>

@foreach ($data['unpaid_balances_coming_up'] as $unpaid_balance)
{{$unpaid_balance->client->name}} ({{$unpaid_balance->package->name}})  {{$unpaid_balance->date}}<br/>
Balance due: £{{$unpaid_balance->balance_requested}}, Balance paid: £{{$unpaid_balance->balance_amount}}<br/><br/>
@endforeach

<br/><br/>
CRON job run at {{date('H:i:s, d-m-Y')}}
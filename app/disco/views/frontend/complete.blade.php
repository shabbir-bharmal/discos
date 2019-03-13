
@extends('frontend.default')

@section('content')
<div class="row">    
    <div class="col-lg-12">
        <h1>Booking complete!</h1>
        
        <p>Thank you for making a booking. Here is a summary of your booking below. </p>
        
        <p>Date of event: {{$booking['date']}}</p>
        <p>Address of event: {{$booking['venue_name']}}, {{$booking['venue_address1']}},{{$booking['venue_address2']}}, {{$booking['venue_address3']}}, {{$booking['venue_postcode']}}</p>
        <p>Total cost: &pound;{{$booking['total_cost']}}</p>
        <p>Deposit to pay: &pound;{{$booking['deposit_requested']}}</p>
    </div>
</div>
@stop
@section('scripts')

<script type="text/javascript">
    $(document).ready(function() {

    });
</script>
@stop
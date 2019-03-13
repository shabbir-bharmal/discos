
@extends('frontend.default')

@section('content')
<div class="row">    
    <div class="col-lg-12">
        <h1>Thank you!</h1>
        <p>You have been sent an email with further details.</p>
        
        @if (\Auth::check())
        
        <p>Total cost calculated at: £{{$booking->total_cost}}</p>
        
        @endif
        
    </div>
</div>
@stop
@extends('admin.default')
@section('content')

<div class="row">    
    <div class="col-lg-12">
        <h1>Payment</h1>

        @include('admin.include.breadcrumb')
    </div>
</div>

<div class="panel panel-info">
    <div class="panel-heading">Payment Process</div>
    <div class="panel-body">
    {{Form::open(array('id' => 'admin-payment-form','route'=> 'confirmed'))}}
                @if (Session::has('payment_status'))
                    <div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
                    {{ Session::get('payment_status') }}
                    </div>
                @endif
             	@if (Session::has('errors'))
                    <div class="alert alert-danger">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> 
						{{ Session::get('errors') }}
                    </div>
                @endif

					<span class="payment-errors text-danger"> </span>
				
            <div class="form-group">
                <label for="card-number">Card number</label>
                <input class="form-control" data-stripe="number" type="text" id="card-number">
            </div>
            <div class="form-group">
                <label for="cardholder-name">Cardholders email</label>
                <input class="form-control" data-stripe="email" name="cus_email" type="email" id="cardholder-email">
            </div>
            <div class="form-group row">
                <label for="expiry-date" class="col-md-12">Expiry date (MM/YY)</label>
                <div class="col-xs-6 col-md-1">
                    <input class="form-control" data-stripe="exp_month" type="text" id="expiry-date">
                </div>
                <div class="col-xs-6 col-md-1">
                    <input class="form-control" data-stripe="exp_year" type="text">
                </div>
            </div>
            <div class="form-group">
                <label for="cvv">CVV</label>
                <input class="form-control" data-stripe="cvc" type="text" id="cvv">
            </div>
            <div class="form-group">
                <label for="deposit_requested">Amount to debit</label>
                <input class="form-control" name="deposit_requested" type="text" id="deposit_requested">
            </div>
            
            <div class="form-group">
                <input class="btn btn-primary submit" type="submit" value="Securely send card details">
            </div>
        {{ Form::close() }}
    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<script type="text/javascript">
Stripe.setPublishableKey('pk_live_c783mAzEAPom7uOf5OzZUZSq');
//pk_test_9bQA4TSB6f0ANY4gwYnbA30T
//pk_live_c783mAzEAPom7uOf5OzZUZSq
$(function() {
    var $form = $('#admin-payment-form');
    $form.submit(function(event) {
        // Disable the submit button to prevent repeated clicks:
        $form.find('.submit').prop('disabled', true);

        // Request a token from Stripe:
        Stripe.card.createToken($form, stripeResponseHandler);

        // Prevent the form from being submitted:
        return false;
    });

});

function stripeResponseHandler(status, response) {
    // Grab the form:
    var $form = $('#admin-payment-form');

    if (response.error) { // Problem!

        // Show the errors on the form:
        $form.find('.payment-errors').text(response.error.message);
        $form.find('.submit').prop('disabled', false); // Re-enable submission

    } else { // Token was created!

        // Get the token ID:
        var token = response.id;

        // Insert the token ID into the form so it gets submitted to the server:
        $form.append($('<input type="hidden" name="stripeToken">').val(token));

        // Submit the form:
        $form.get(0).submit();
    }
};
</script>
@endsection
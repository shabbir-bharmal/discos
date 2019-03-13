@extends('admin.default')
@section('content')

<div class="row">    
    <div class="col-lg-12">
        <h1>New quote</h1>

        @include('admin.include.breadcrumb')
    </div>
</div>

<div class="panel panel-success">
    <div class="panel-heading">Availability Confirmed</div>
    <div class="panel-body">
        <p>We have the following bookings on {{ Helpers\DateTimeHelper::us_to_uk_date($booking->date) }}</p>
        <table class="table table-hover">
            @if ($events->count())
                @foreach ($events as $event)
                    <tr>
                        <td>{{ $event->start_time }}</td>
                        <td>{{ $event->finish_time }}</td>
                        <td>{{ $event->venue_postcode }}</td>
                        <td>{{ $event->occasion }}</td>
                        <td>{{ $event->equipmentSet->name }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5">No bookings to display</td>
                </tr>
            @endif
        </table>
    </div>
</div>



<div class="panel panel-info">
    <div class="panel-heading">Step 2 - Customer details</div>
    <div class="panel-body">
        <div class="col-lg-6" >
        {{ Form::open(['url' => 'admin/quote/step2']) }}
            <div class="form-group">
                {{ Form::label('name', 'Customer Name') }}
                {{ Form::text('name', Input::old('name'), ['class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('email', 'Email') }}
                {{ Form::text('email', Input::old('email'), ['class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('telephone', 'Telephone') }}
                {{ Form::text('telephone', Input::old('telephone'), ['class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('mobile', 'Mobile') }}
                {{ Form::text('mobile', Input::old('mobile'), ['class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('heard_about', 'Where did you hear about us') }}
                {{ Form::text('heard_about', Input::old('heard_about'), ['class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('email_quote', 'Would you like the quotation sent to your email') }}
                {{ Form::checkbox('email_quote', 1, Input::old('email_quote'), ['class' => 'form-control']) }}
            </div>
            <div class="form-group">
                <input class="btn btn-primary" type="submit" value="Save customer">
            </div>
        {{ Form::close() }}
        </div>
        <div class="col-lg-6" >

            <div class="panel panel-warning">
                <div class="panel-heading">Package Description</div>
                <div class="panel-body">
                    <div>{{ $booking->package->name }}<br>{{ $booking->package->description }}</div>
                    <hr>
                    <div>The total cost of this package is £{{ $booking->total_cost }}</div>
                    <div>
                        The deposit due to make a provisional booking is £{{ $booking->package->deposit }}.  The balance of £{{ $booking->total_cost - $booking->package->deposit }} is due {{ $booking->package->due_date ? $booking->package->due_date . ' days before the event.' : 'on the day' }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $('#name').autocomplete({
        source: 'clients',
        select: function(e, ui) {
            console.log(ui);
            var customer = ui.item;

            $('#email').val(customer.email)
            $('#telephone').val(customer.telephone)
            $('#mobile').val(customer.mobile)
            $('#heard_about').val(customer.heard_about)
        }
    });
</script>
@endsection
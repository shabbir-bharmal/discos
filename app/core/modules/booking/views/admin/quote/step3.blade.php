@extends('admin.default')
@section('content')

<div class="row">    
    <div class="col-lg-12">
        <h1>New quote</h1>

        @include('admin.include.breadcrumb')
    </div>
</div>

<div class="panel panel-info">
    <div class="panel-heading">Step 3 - Customer details (continued)</div>
    <div class="panel-body">
        {{ Form::open(['url' => 'admin/quote/step3']) }}
            <div class="alert alert-success">
                <strong>Success!</strong> The customer has been saved.
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('address1', 'Home address (line 1)') }}
                        {{ Form::text('address1', Input::old('address1'), ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('address2', 'Home address (line 2)') }}
                        {{ Form::text('address2', Input::old('address2'), ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('address3', 'Town') }}
                        {{ Form::text('address3', Input::old('address3'), ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('address4', 'County') }}
                        {{ Form::text('address4', Input::old('address4'), ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('postcode', 'Postcode') }}
                        {{ Form::text('postcode', Input::old('postcode'), ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('venue_name', 'Venue name') }}
                        {{ Form::text('venue_name', $booking->venue_name, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('venue_address1', 'Venue street') }}
                        {{ Form::text('venue_address1', Input::old('venue_address1'), ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('venue_address2', 'Venue town') }}
                        {{ Form::text('venue_address2', Input::old('venue_address2'), ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('venue_address3', 'Venue county') }}
                        {{ Form::text('venue_address3', Input::old('venue_address3'), ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('venue_postcode', 'Venue postcode') }}
                        {{ Form::text('venue_postcode', $booking->venue_postcode, ['class' => 'form-control']) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                {{ Form::label('event_occasion', 'Occasion') }}
                {{ Form::select('event_occasion', $occasions, Input::old('event_occasion'), ['class' => 'form-control']) }}
                <p class="text-danger">{{$errors->first('occasion')}}</p>
            </div>
            @foreach (Booking::further_details()  as $occasion => $occasion_details)
                <div id="{{$occasion}}" class="further_details">

                    @foreach ($occasion_details  as $key => $label)

                        <div class="form-group">
                            <?php
                            echo Form::label($key, $label);
                            echo Form::text($key, $booking->$key, array('class' => 'form-control'));
                            ?>
                        </div>

                    @endforeach
                </div>
            @endforeach
            {{--<div class="form-group">--}}
                {{--{{ Form::label('bride_firstname', 'Bride\'s first name') }}--}}
                {{--{{ Form::text('bride_firstname', Input::old('bride_firstname'), ['class' => 'form-control']) }}--}}
            {{--</div>--}}
            {{--<div class="form-group">--}}
                {{--{{ Form::label('groom_firstname', 'Groom\'s first name') }}--}}
                {{--{{ Form::text('groom_firstname', Input::old('groom_firstname'), ['class' => 'form-control']) }}--}}
            {{--</div>--}}

            {{--<div class="form-group">--}}
                {{--{{ Form::label('groom_surname', 'Groom\'s surname') }}--}}
                {{--{{ Form::text('groom_surname', Input::old('groom_surname'), ['class' => 'form-control']) }}--}}
            {{--</div>--}}
            {{--<div class="form-group">--}}
                {{--{{ Form::label('birthday_name', 'Birthday person\'s name') }}--}}
                {{--{{ Form::text('birthday_name', Input::old('birthday_name'), ['class' => 'form-control']) }}--}}
            {{--</div>--}}
            {{--<div class="form-group">--}}
                {{--{{ Form::label('birthday_age', 'Birthday person\'s age') }}--}}
                {{--{{ Form::text('birthday_age', Input::old('birthday_age'), ['class' => 'form-control']) }}--}}
            {{--</div>--}}
            <div class="form-group">
                {{ Form::label('notes', 'Additional notes?') }}
                {{ Form::textarea('notes', Input::old('notes'), ['class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::submit('Save booking', ['class' => 'btn btn-primary']) }}
            </div>
        {{ Form::close() }}
    </div>
</div>

@endsection

@section('scripts')
    <script>
        $('#event_occasion').change(function(){
            showFurtherDetails();
        });

        showFurtherDetails();

        function showFurtherDetails()
        {
            $('.further_details').hide();

            switch ($('#event_occasion').val()) {
                case 'birthday':
                case 'wedding':
                    $('#' + $('#event_occasion').val()).show();
                    break;
            }
        }
    </script>
@endsection
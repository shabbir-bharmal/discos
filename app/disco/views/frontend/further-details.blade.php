
@extends('frontend.default')

@section('content')
<div class="row">    
    <div class="col-lg-12">
        <h1>Your booking <small>Some final details</small></h1>        
    </div>
    <?php echo Form::open(array('url' => 'booking', 'method' => 'post')); ?>
    <div class="col-lg-6">
        <div class="panel panel-info">
            <div class="panel-heading">
                Event details
            </div>
            <div class="panel-body">
                <div class='form-group'>
                    <?php
                    $venue_name = (\Input::old('venue_name') != '') ? \Input::old('venue_name') : $booking->venue_name;
                    echo Form::label('venue_name', 'Venue Name');
                    echo Form::text('venue_name', $venue_name, array('class' => 'form-control'));
                    ?>
                    <p class='text-danger'>{{$errors->first('venue_name')}}</p>
                </div>
                <div class='form-group'>
                    <?php
                    echo Form::label('venue_address1', 'Venue Street');
                    echo Form::text('venue_address1', \Input::old('venue_address1'), array('class' => 'form-control'));
                    ?>
                    <p class='text-danger'>{{$errors->first('venue_address1')}}</p>
                </div>
                <div class='form-group'>
                    <?php
                    echo Form::label('venue_address2', 'Venue Town');
                    echo Form::text('venue_address2', \Input::old('venue_address2'), array('class' => 'form-control'));
                    ?>
                    <p class='text-danger'>{{$errors->first('venue_address2')}}</p>
                </div>
                <div class='form-group'>
                    <?php
                    echo Form::label('venue_address3', 'Venue County');
                    echo Form::text('venue_address3', \Input::old('venue_address3'), array('class' => 'form-control'));
                    ?>
                    <p class='text-danger'>{{$errors->first('venue_address3')}}</p>
                </div>
                <div class='form-group'>
                    <?php
                    $venue_postcode = (\Input::old('venue_postcode') != '') ? \Input::old('venue_postcode') : $booking->venue_postcode;
                    echo Form::label('venue_postcode', 'Venue Postcode');
                    echo Form::text('venue_postcode', $venue_postcode, array('class' => 'form-control'));
                    ?>
                    <p class='text-danger'>{{$errors->first('venue_postcode')}}</p>
                </div>
                <div class='form-group'>
                    <?php
                    echo Form::label('event_occasion', 'Occasion');
                    echo Form::select('event_occasion', $occasions, '', array('class' => 'form-control'));
                    ?>
                </div>
                @foreach ($further_details  as $occasion => $occasion_details)
                <div id="{{$occasion}}" class="further_details">

                    @foreach ($occasion_details  as $key => $label)

                    <div class='form-group'>
                        <?php
                        echo Form::label($key, $label);
                        echo Form::text($key, '', array('class' => 'form-control'));
                        ?>
                    </div>

                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel panel-success">
            <div class="panel-heading">
                Your details
            </div>
            <div class="panel-body">
                <div class='form-group'>
                    <?php
                    $name = (\Input::old('name') != '') ? \Input::old('name') : $booking->client->name;
                    echo Form::label('name', 'Full name');
                    echo Form::text('name', $name, array('class' => 'form-control'));
                    ?>
                    <p class='text-danger'>{{$errors->first('name')}}</p>
                </div>
                <div class='form-group'>
                    <?php
                    echo Form::label('address1', 'Street');
                    echo Form::text('address1', \Input::old('address1'), array('class' => 'form-control'));
                    ?>
                    <p class='text-danger'>{{$errors->first('address1')}}</p>
                </div>
                <div class='form-group'>
                    <?php
                    echo Form::label('address2', 'Town');
                    echo Form::text('address2', \Input::old('address2'), array('class' => 'form-control'));
                    ?>
                    <p class='text-danger'>{{$errors->first('address2')}}</p>
                </div>
                <div class='form-group'>
                    <?php
                    echo Form::label('address3', 'County');
                    echo Form::text('address3', \Input::old('address3'), array('class' => 'form-control'));
                    ?>
                    <p class='text-danger'>{{$errors->first('address3')}}</p>
                </div>
                <div class='form-group'>
                    <?php
                    echo Form::label('postcode', 'Postcode');
                    echo Form::text('postcode', \Input::old('postcode'), array('class' => 'form-control'));
                    ?>
                    <p class='text-danger'>{{$errors->first('postcode')}}</p>
                </div>
            </div>
        </div>
        <div class='form-group'>
            <?php echo Form::submit('Complete', array('class' => 'btn btn-primary')); ?>
            <?php echo Form::reset('Clear form', array('class' => 'btn btn-default')); ?>

        </div>
    </div>
    <?php echo Form::close() ?>
</div>
@stop
@section('scripts')

<script type="text/javascript">

    $(document).ready(function() {

        $('#event_occasion').change(function() {
            showFurtherDetails();
        });

        showFurtherDetails();
    });

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
@stop
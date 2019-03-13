
@extends('frontend.default')

@section('content')
<div class="row">    
    <div class="col-lg-12">
        <h1>Disco Bookings <small>Get a quotation today!</small></h1>
        <?php if($errors->first('availability') != ''): ?>
        <div class="alert alert-danger">
            {{$errors->first('availability')}}
        </div>
        <?php endif; ?>
    </div>
    <?php echo Form::open(array('url' => '/', 'method' => 'post')); ?>
    <div class="col-lg-6">
        <div class="panel panel-info">
            <div class="panel-heading">
                Event details
            </div>
            <div class="panel-body">
                <div class='form-group'>
                    <?php
                    echo Form::label('date', 'Date of your event');
                    echo Form::text('date', \Input::old('date'), array('class' => 'form-control datepicker'));
                    ?>
                    <p class='text-danger'>{{$errors->first('date')}}</p>
                </div>
                <div class='form-group'>
                    <?php
                    echo Form::label('start_time', 'Start time');
                    echo Form::text('start_time', \Input::old('start_time'), array('class' => 'form-control timepicker'));
                    ?>
                    <p class='text-danger'>{{$errors->first('start_time')}}</p>
                </div>
                <div class='form-group'>
                    <?php
                    echo Form::label('finish_time', 'Finish time');
                    echo Form::text('finish_time', \Input::old('finish_time'), array('class' => 'form-control timepicker'));
                    ?>
                    <p class='text-danger'>{{$errors->first('finish_time')}}</p>
                </div>
                <div class='form-group'>
                    <?php
                    echo Form::label('venue_name', 'Name of the venue');
                    echo Form::text('venue_name', \Input::old('venue_name'), array('class' => 'form-control'));
                    ?>
                    <p class='text-danger'>{{$errors->first('venue_name')}}</p>
                </div>
                <div class='form-group'>
                    <?php
                    echo Form::label('venue_postcode', 'Postcode of venue');
                    echo Form::text('venue_postcode', \Input::old('venue_postcode'), array('class' => 'form-control'));
                    ?>
                    <p class='text-danger'>{{$errors->first('venue_postcode')}}</p>
                </div>
                <div class='form-group'>
                    <?php
                    echo Form::label('occasion', 'Occasion');
                    echo Form::text('occasion', \Input::old('occasion'), array('class' => 'form-control'));
                    ?>
                    <p class='text-danger'>{{$errors->first('occasion')}}</p>
                </div>

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
                    echo Form::label('name', 'Your Name');
                    echo Form::text('name', \Input::old('name'), array('class' => 'form-control', 'placeholder' => 'Joe Dunne'));
                    ?>
                    <p class='text-danger'>{{$errors->first('name')}}</p>
                </div>
                <div class='form-group'>
                    <?php
                    echo Form::label('email', 'Email');
                    echo Form::text('email', \Input::old('email'), array('class' => 'form-control', 'placeholder' => 'example@gmail.com'));
                    ?>
                    <p class='text-danger'>{{$errors->first('email')}}</p>
                </div>
                <div class='form-group'>
                    <?php
                    echo Form::label('telephone', 'Telephone');
                    echo Form::text('telephone', \Input::old('telephone'), array('class' => 'form-control', 'placeholder' => '0123456789'));
                    ?>
                    <p class='text-danger'>{{$errors->first('telephone')}}</p>
                </div>
                <div class='form-group'>
                    <?php
                    echo Form::label('mobile', 'Mobile');
                    echo Form::text('mobile', \Input::old('mobile'), array('class' => 'form-control', 'placeholder' => '07777888999'));
                    ?>
                    <p class='text-danger'>{{$errors->first('mobile')}}</p>
                </div>
                <div class='form-group'>
                    <?php
                    echo Form::label('heard_about', 'Where did you hear about us?');
                    echo Form::text('heard_about', \Input::old('heard_about'), array('class' => 'form-control'));
                    ?>
                </div>
                <div class='form-group'>
                    <?php
                    echo Form::label('ref_no', 'Reference number (if any)');
                    echo Form::text('ref_no', \Input::old('ref_no'), array('class' => 'form-control'));
                    ?>
                </div>

            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                Choose a package
            </div>
            <div class="panel-body">
                <div class='form-group'>
                    <?php
                    echo Form::select('package_name', $packages, \Input::old('package_name'), array('class' => 'form-control'));
                    ?>
                    <p class='text-danger'>{{$errors->first('package_name')}}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8 col-md-offset-4">
        <div class='form-group'>
            <?php echo Form::submit('Get quotation', array('class' => 'btn btn-primary')); ?>
            <?php echo Form::reset('Clear form', array('class' => 'btn btn-default')); ?>
            <?php echo Form::close() ?>
        </div>
    </div>
</div>
@stop
@section('scripts')

<script type="text/javascript">

    $(document).ready(function() {

        if ($('.datepicker').length > 0) {
            $('.datepicker').datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd-mm-yy'

            });
        }

        $('.timepicker').timepicker();
    });
</script>
@stop

@extends('frontend.default')

@section('content')
<div class="row">    
    <div class="col-lg-12">
        <h1>Your booking <small>Please verify yourself</small></h1>
    </div>
    <div class="col-lg-12">
        <?php if (count($errors) > 0) : ?>
            <div class="alert alert-danger">
                {{$errors->first('invalid')}}
            </div>
        <?php endif; ?>
        <?php echo Form::open(array('url' => 'validate/' . $token, 'method' => 'post')); ?>
        <div class='form-group'>
            <?php
            echo Form::label('date', 'Please just confirm your event date');
            echo Form::text('date', '', array('class' => 'form-control datepicker'));
            ?>
        </div>

        <div class='form-group'>
            <?php echo Form::submit('Verify', array('class' => 'btn btn-primary')); ?>
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
    });
</script>
@stop
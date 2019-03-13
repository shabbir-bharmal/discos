
@extends('admin.crud')

@section('title')
<h1>All packages <small>Package management</small></h1>
@stop

@section('update_form')

<?php echo Form::open(array('url' => 'admin/packages/package', 'method' => 'post', 'id' => 'form-submit')); ?>
<input type='hidden' name='id' />
<div class='form-group'>
    <?php
    echo Form::label('name', 'Name');
    echo Form::text('name', '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('day', 'Day(s) of week');
    echo Form::text('day', '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('start_time', 'Start time');
    echo Form::text('start_time', '', array('class' => 'form-control timepicker'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('finish_time', 'Finish time');
    echo Form::text('finish_time', '', array('class' => 'form-control timepicker'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('min_price', 'Minimum price');
    echo Form::text('min_price', '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('max_price', 'Maximum price');
    echo Form::text('max_price', '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('hours_inc', 'Hours included');
    echo Form::text('hours_inc', '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('overtime_cost', 'Overtime cost');
    echo Form::text('overtime_cost', '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('overtime_interval', 'Overtime interval (in mins)');
    echo Form::text('overtime_interval', '30', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('free_travel', 'Free travel time (in mins)');
    echo Form::text('free_travel', '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('travel_cost', 'Travel cost');
    echo Form::text('travel_cost', '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('travel_interval', 'Travel interval (in mins)');
    echo Form::text('travel_interval', '30', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('setup_time', 'Setup Equipment Time (in mins)');
    echo Form::text('setup_time', '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('email_template_id', 'Email template');
    echo Form::select('email_template_id', $email_templates, '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('deposit', 'Default deposit');
    echo Form::text('deposit', '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('due_date', 'Default due date');
    echo Form::text('due_date', '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('description', 'Description');
    echo Form::textarea('description', '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('valid_from', 'Valid From');
    echo Form::text('valid_from', '', array('class' => 'form-control datepicker', 'placeholder' => 'dd-mm-yyyy'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('valid_to', 'Valid To');
    echo Form::text('valid_to', '', array('class' => 'form-control datepicker' , 'placeholder' => 'dd-mm-yyyy'));
    ?>
</div>

@stop
@section('all_table')
<thead>
    <tr>
        <th>Name <i class="fa fa-sort"></i></th>
        <th>Day(s) <i class="fa fa-sort"></i></th>
        <th>Start <i class="fa fa-sort"></i></th>
        <th>Finish <i class="fa fa-sort"></i></th>
    </tr>
</thead>
<tbody>

    @foreach ($packages as $package)

    <tr data-id='{{$package->id}}'>
        <td>{{$package->name}}</td>
        <td>{{$package->day}}</td>
        <td>{{$package->start_time}}</td>
        <td>{{$package->finish_time}}</td>
    </tr>

    @endforeach
</tbody>

@stop


@section('scripts')
<script type="text/javascript">

    var objectType = 'package';

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
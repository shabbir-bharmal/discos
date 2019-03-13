
@extends('admin.crud')

@section('title')
<h1>All offer emails</h1>
@stop

@section('update_form')

<?php echo Form::open(array('url' => 'admin/email-offers/edit', 'method' => 'post', 'id' => 'form-submit')); ?>
<input type='hidden' name='id' />
<div class='form-group'>
    <?php
    echo Form::label('template_id', 'Email template');
    echo Form::select('template_id', EmailTemplate::get_offer_selection(), '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('booking_type', 'Booking Type');
    echo Form::select('booking_type', EmailOffer::get_booking_type_selection(), '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('from_date', 'Bookings From');
    echo Form::text('from_date', '', array('class' => 'form-control datepicker', 'placeholder' => 'dd-mm-yyyy'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('end_date', 'Bookings To');
    echo Form::text('end_date', '', array('class' => 'form-control datepicker' , 'placeholder' => 'dd-mm-yyyy'));
    ?>
</div>

<div class='form-group'>
    <?php
    echo Form::label('date', 'Schedule Date');
    echo Form::text('date', '', array('class' => 'form-control datepicker' , 'placeholder' => 'dd-mm-yyyy'));
    ?>
</div>

<div class='form-group'>
    <?php
    echo Form::label('run_hour', 'Hour at which to send');
    echo Form::selectRange('run_hour', 0, 23, '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <p>
        <label>
            Live
            {{ Form::checkbox('status', 1, 0) }}
        </label>

    </p>
</div>

@stop
@section('all_table')
<thead>
    <tr>
        <th>Name</th>
        <th>Type</th>
        <th>Bookings Range</th>
        <th>Execution Date</th>
        <th>Status</th>
    </tr>
</thead>
<tbody>

    @foreach ($schedules as $schedule)

    <tr data-id='{{$schedule->id}}'>
        <td>{{$schedule->title}}</td>
        <td>{{ucfirst($schedule->booking_type)}}</td>
        <td>{{$schedule->from_date}} - {{$schedule->end_date}}</td>
        <td>{{$schedule->date}}</td>
        <td>{{$schedule->status?'YES':''}}</td>
    </tr>

    @endforeach
</tbody>

@stop


@section('scripts')
<script type="text/javascript">
    var objectType = 'email-offer';
    
    $(document).ready(function(){
       
        $('#regularity').change(function(){
            set_regularity_fields();
        });
        
        set_regularity_fields();
    });
    
    function set_regularity_fields()
    {           
        switch($('#regularity').val()) {

         case 'daily':
             $('#day_of_week, #day_of_month').val('');  
             $('.day_of_week, .day_of_month').hide();                
             break;
         case 'monthly':                
             $('#day_of_week').val('');    
             $('.day_of_week').hide();
             $('.day_of_month').show();
             break;               
         case 'weekly':                
             $('#day_of_month').val(''); 
             $('.day_of_month').hide(); 
             $('.day_of_week').show();  
             break;
        }
    }
    

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
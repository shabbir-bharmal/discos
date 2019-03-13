
@extends('admin.crud')

@section('title')
<h1>All scheduled emails</h1>
@stop

@section('update_form')

<?php echo Form::open(array('url' => 'admin/schedules/edit', 'method' => 'post', 'id' => 'form-submit')); ?>
<input type='hidden' name='id' />
<div class='form-group'>
    <?php
    echo Form::label('id', 'Email template');
    echo Form::select('id', EmailTemplate::get_schedule_selection(), '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('regularity', 'Regularity');
    echo Form::select('regularity', EmailTemplate::get_regularity_selection(), '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group day_of_week'>
    <?php
    echo Form::label('day_of_week', 'Day of week (for weekly)');
    echo Helpers\DateTimeHelper::selectDays('day_of_week', '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group day_of_month'>
    <?php
    echo Form::label('day_of_month', 'Day of month (for monthly)');
    echo Form::selectRange('day_of_month', 1, 31, '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('execution_hour', 'Hour at which to send');
    echo Form::selectRange('execution_hour', 0, 23, '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <p>
        <label>
            Live
            {{ Form::checkbox('scheduled', 1, 0) }}
        </label>

    </p>
</div>

@stop
@section('all_table')
<thead>
    <tr>
        <th>Name</th>
        <th>Regularity</th>
        <th>Time</th>
        <th>Live</th>
        <th>Last run</th>
    </tr>
</thead>
<tbody>

    @foreach ($schedules as $schedule)

    <tr data-id='{{$schedule->id}}'>
        <td>{{$schedule->name}}</td>
        <td>{{ucfirst($schedule->regularity)}}</td>
        <td>{{$schedule->execution_hour}}:00</td>
        <td>{{$schedule->scheduled?'YES':''}}</td>
        <td>{{$schedule->last_schedule}}</td>
    </tr>

    @endforeach
</tbody>

@stop


@section('scripts')
<script type="text/javascript">
    var objectType = 'schedule';
    
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
    
    
    
</script>
@stop

@extends('admin.crud')

@section('title')
<h1>All package rules <small>Package management</small></h1>
@stop

@section('update_form')

<?php echo Form::open(array('url' => 'admin/packages/rules', 'method' => 'post', 'id' => 'form-submit')); ?>
<input type='hidden' name='id' />
<div class='form-group'>
    <?php
    echo Form::label('name', 'Name');
    echo Form::text('name', '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('date_from', 'Rule starts on');
    echo Form::text('date_from', '', array('class' => 'form-control datepicker'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('date_to', 'Rule ends on');
    echo Form::text('date_to', '', array('class' => 'form-control datepicker'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('package_id', 'Package to use');
    echo Form::select('package_id', $packages, '', array('class' => 'form-control'));
    ?>
</div>

@stop
@section('all_table')
<thead>
    <tr>
        <th>Name <i class="fa fa-sort"></i></th>
        <th>From <i class="fa fa-sort"></i></th>
        <th>To <i class="fa fa-sort"></i></th>
        <th>Package</th>
            
    </tr>
</thead>
<tbody>

    @foreach ($rules as $rule)

    <tr data-id='{{$rule->id}}'>
        <td>{{$rule->name}}</td>
        <td>{{$rule->date_from}}</td>
        <td>{{$rule->date_to}}</td>
        <td>
            @if ($rule->package_id == 0)
                Unavailable
            @else            
                {{$rule->package->name. " (".$rule->package->day . ") " . $rule->package->start_time . "-" . $rule->package->finish_time}}
            @endif
            </td>
    </tr>

    @endforeach
</tbody>

@stop


@section('scripts')
<script type="text/javascript">
    var objectType = 'rule';
</script>
@stop
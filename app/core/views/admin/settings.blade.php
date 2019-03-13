
@extends('admin.crud')

@section('title')
    <h1>All settings <small>Setting management</small></h1>
@stop

@section('update_form')
<?php echo Form::open(array('url' => 'admin/settings/setting', 'method' => 'post', 'id' => 'form-submit')); ?>
<input type='hidden' name='id' />

<div class='form-group'>
    <?php
    echo Form::label('key', 'Key');
    echo Form::text('key', '', array('class' => 'form-control', 'placeholder' => 'No spaces allowed'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('value', 'Value');
    echo Form::text('value', '', array('class' => 'form-control', 'placeholder' => ''));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('notes', 'Notes');
    echo Form::textarea('notes', '', array('class' => 'form-control'));
    ?>
</div>

@stop
@section('all_table')
<thead>
    <tr>
        <th>Key <i class="fa fa-sort"></i></th>
        <th>Value <i class="fa fa-sort"></i></th>
    </tr>
</thead>
<tbody>

    @foreach ($settings as $setting)

    <tr data-id='{{$setting->id}}'>
        <td>{{$setting->key}}</td>
        <td>{{$setting->value}}</td>
    </tr>

    @endforeach

</tbody>
@stop


@section('scripts')
<script type="text/javascript">
    var objectType = 'setting';    
</script>
@stop
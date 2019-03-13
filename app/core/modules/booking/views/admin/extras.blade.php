
@extends('admin.crud')

@section('title')
<h1>All extras <small>Additional Extras</small></h1>
@stop

@section('update_form')

<?php echo Form::open(array('url' => 'admin/extras/extra', 'method' => 'post', 'id' => 'form-submit')); ?>
<input type='hidden' name='id' />
<div class='form-group'>
    <?php
    echo Form::label('name', 'Name');
    echo Form::text('name', '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('description', 'Description');
    echo Form::text('description', '', array('class' => 'form-control'));
    ?>
</div>
@stop
@section('all_table')
<thead>
    <tr>
        <th>Name <i class="fa fa-sort"></i></th>
        <th>Description <i class="fa fa-sort"></i></th>
    </tr>
</thead>
<tbody>

    @foreach ($extras as $extra)

    <tr data-id='{{$extra->id}}'>
        <td>{{$extra->name}}</td>
        <td>{{$extra->description}}</td>
    </tr>

    @endforeach
</tbody>

@stop


@section('scripts')
<script type="text/javascript">
    var objectType = 'extra';
</script>
@stop
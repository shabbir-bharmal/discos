

@extends('admin.crud')

@section('title')
<h1>All contracts <small>Contract management</small></h1>
@stop

@section('update_form')
<?php echo Form::open(array('url' => 'admin/contracts/contract', 'method' => 'post', 'id' => 'form-submit')); ?>

<div class='form-group'>
    <?php
    echo Form::label('name', 'Name');
    echo Form::text('name', '', array('class' => 'form-control', 'placeholder' => 'Joe Dunne'));
    ?>
</div>

@stop
@section('all_table')
<thead>
    <tr>
        <th>Name <i class="fa fa-sort"></i></th>
        <th>Email <i class="fa fa-sort"></i></th>
    </tr>
</thead>
<tbody>

    @foreach ($contracts as $contract)

    <tr>
        <td>{{$contract->name}}</td>
        <td>{{$contract->day}}</td>
    </tr>

    @endforeach

</tbody>@stop


@section('scripts')
<script type="text/javascript">
    var objectType = 'contract';
</script>
@stop

@extends('admin.crud')

@section('title')
<h1>All clients <small>Client management</small></h1>
@stop

@section('update_form')

<?php echo Form::open(array('url' => 'admin/clients/client', 'method' => 'post', 'id' => 'form-submit')); ?>
<input type='hidden' name='id' />
<div class='form-group'>
    <?php
    echo Form::label('name', 'Name');
    echo Form::text('name', '', array('class' => 'form-control', 'placeholder' => 'Joe Dunne'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('email', 'Email');
    echo Form::text('email', '', array('class' => 'form-control', 'placeholder' => 'example@gmail.com'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('telephone', 'Home Tel');
    echo Form::text('telephone', '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('mobile', 'Mobile Tel');
    echo Form::text('mobile', '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('address1', 'House No / Street Name');
    echo Form::text('address1', '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('address2', 'District');
    echo Form::text('address2', '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('address3', 'Town');
    echo Form::text('address3', '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('address4', 'County');
    echo Form::text('address4', '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('postcode', 'Postcode');
    echo Form::text('postcode', '', array('class' => 'form-control'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('heard_about', 'Where did you hear about us?');
    echo Form::text('heard_about', '', array('class' => 'form-control'));
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

    @foreach ($clients as $client)

    <tr data-id='{{$client->id}}'>
        <td>{{$client->name}}</td>
        <td>{{$client->email}}</td>
    </tr>

    @endforeach

</tbody>
@stop


@section('scripts')
<script type="text/javascript">
    var objectType = 'client';
</script>
@stop
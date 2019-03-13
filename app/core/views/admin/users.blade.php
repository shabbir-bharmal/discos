
@extends('admin.crud')

@section('title')
<h1>All users <small>User management</small></h1>
@stop

@section('update_form')

@include('admin/include/notifications')

{{ Form::open(array('url' => 'admin/users/user', 'method' => 'post', 'id' => 'form-submit')) }}
<input type='hidden' name='id' />
<div class='form-group'>
    <?php
    echo Form::label('name', 'Name');
    echo Form::text('name', Input::old('name'), array('class' => 'form-control', 'placeholder' => 'Joe Dunne', 'autocomplete' => 'off'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('username', 'Username');
    echo Form::text('username', Input::old('username'), array('class' => 'form-control', 'autocomplete' => 'off'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('password', 'Password');
    echo Form::password('password', array('class' => 'form-control', 'autocomplete' => 'off'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('password_confirmation', 'Password confirmation');
    echo Form::password('password_confirmation', array('class' => 'form-control', 'autocomplete' => 'off'));
    ?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('roles', 'Role');
	echo Form::select('role', array('Admin' => 'Admin', 'Editor' => 'Staff'), 'Staff', ['class' => 'form-control', 'autocomplete' => 'off']);
	//echo Form::select('roles[]', $roles, null, ['class' => 'form-control roles', 'autocomplete' => 'off']);
	?>
</div>
<div class='form-group'>
    <?php
    echo Form::label('login_link', 'Login link');
    echo Form::text('login_link', Input::old('login_link'), array('class' => 'form-control', 'autocomplete' => 'off', 'readonly'));
    ?>
</div>

@stop
@section('all_table')
<thead>
    <tr>
        <th>Name</th>
        <th>Username</th>
        <th>Role</th>
        <th></th>
    </tr>
</thead>
<tbody>

    @foreach ($users as $user)
    <tr data-id='{{$user->id}}'>
        <td>{{$user->name}}</td>
        <td>{{$user->username}}</td>
        <td> @if($user->roles()->first()->name == 'Editor') Staff @else {{$user->roles()->first()->name}} @endif </td>
        <td><a href="{{ action('UserController@getRegenerateLoginToken', $user->id) }}" class="btn btn-warning">Regenerate Login token</a></td>
    </tr>

    @endforeach

</tbody>
@stop


@section('scripts')
<script type="text/javascript">
    var objectType = 'user';
</script>
@stop
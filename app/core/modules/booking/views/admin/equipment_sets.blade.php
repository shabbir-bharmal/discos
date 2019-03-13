
@extends('admin.crud')

@section('title')
<h1>All Equipment <small>Equipment set management</small></h1>
@stop

@section('update_form')

{{ Form::open(array('url' => 'admin/sets/set', 'method' => 'post', 'id' => 'form-submit')); }}
    <input type="hidden" name="id" />
    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', '', array('class' => 'form-control')) }}
    </div>
    <div class="form-group">
        {{ Form::label('description', 'Description') }}
        {{ Form::text('description', '', array('class' => 'form-control')) }}
    </div>
    <h4>Packages</h4>    
    @foreach (Package::all() as $package)

    <label style="font-weight: normal;">
        {{ Form::checkbox('packages[]', $package->id) }}
        {{$package->getFullDescription()}}
    </label><br/>

    @endforeach

@stop
@section('all_table')
<thead>
    <tr>
        <th>Name <i class="fa fa-sort"></i></th>
        <th>Description <i class="fa fa-sort"></i></th>
    </tr>
</thead>
<tbody>

    @foreach ($sets as $set)

    <tr data-id="{{ $set->id }}">
        <td>{{ $set->name }}</td>
        <td>{{ $set->description }}</td>
    </tr>

    @endforeach
</tbody>

@stop


@section('scripts')
<script type="text/javascript">
    var objectType = 'set';
</script>
@stop
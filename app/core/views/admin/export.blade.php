
@extends('admin.default')

@section('content')
<div class="row">    
    <div class="col-lg-12">
        <h1>Exports</h1>

        @include('admin.include.breadcrumb')
    </div>
</div>
<div class="row">    
    <div class="col-lg-12 panel">

        {{$errors->first('export', '<div class="alert alert-danger">:message</div>')}}

        {{Form::open()}}

        <div class="row">

            <div class="col-sm-12">
                <p><input type='submit' class='btn btn-primary' value='Export' />
                    <label>
                        {{Form::checkbox('only_fresh_records', 1, 0)}}
                        Export only records not already exported
                    </label>
                    <label>
                        {{Form::checkbox('mark_as_done', 1, 0)}}
                        Mark exports as exported
                    </label>

                </p>
            </div>


            @foreach ($fields as $model => $attributes)

            <div class="col-md-4">
                <div class="panel panel-info">
                    <div class="panel-heading"><h4>{{ucfirst($model)}}</h4></div>
                    <div class="panel-body">
                        @foreach ($attributes as $field)
                        <div class='row'>
                            <div class='col-lg-3'>

                                <div class='form-group checkbox'>
                                    <label>
                                        <input type="checkbox" name="{{$model}}[{{$field}}]" value="1" />
                                        {{ucfirst(str_replace("_"," ",$field))}}
                                    </label>
                                </div>
                            </div>
                            <div class='col-lg-9'>
                                @if (in_array($field, array_keys(Booking::$datatypes)))
                                <div class='form-group input-group'>
                                    <span class="input-group-addon"><i class="fa">=</i></span>
                                    <input type="text" name="filter[{{$model}}][{{$field}}][=]" class='datepicker form-control'/>
                                </div>
                                <div class='form-group input-group'>
                                    <span class="input-group-addon"><i class="fa">></i></span>
                                    <input type="text" name="filter[{{$model}}][{{$field}}][>]" class='datepicker form-control'/>
                                </div>
                                <div class='form-group input-group'>
                                    <span class="input-group-addon"><i class="fa"><</i></span>
                                    <input type="text" name="filter[{{$model}}][{{$field}}][<]" class='datepicker form-control'/>
                                </div>

                                @else
                                <div class='form-group input-group'>
                                    <span class="input-group-addon"><i class="fa">=</i></span>
                                    <input type="text" name="filter[{{$model}}][{{$field}}][=]" class='form-control'/>
                                </div>

                                @endif
                            </div>

                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach

        </div>


    </div>
    {{Form::close()}}
</div>

@stop


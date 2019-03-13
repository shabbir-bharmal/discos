
@extends('admin.default')

@section('content')

<div class="row">    
    <div class="col-lg-12">
        @yield('title')
        
        @include('admin.include.breadcrumb')
    </div>
</div>
<div class="row">
    <div class="col-lg-12 panel">
        @if (isset($add_available) ? $add_available : true)
            <button class="add-new btn btn-success">+ Add new</button>
        @endif
    </div>
    <div class="col-lg-4">
        <div class="panel panel-primary" id='update-form'>
            <div class="panel-heading">
                <h3 class="panel-title">Add / Edit item</h3>
            </div>
            <div class="panel-body">
                
                @yield('update_form')
                
                <div class='form-group'>
                    {{ Form::submit('Save', array('class' => 'btn btn-primary')) }}
                    @if (isset($clear_available) ? $clear_available : true)
                    {{ Form::reset('Clear', array('class' => 'btn btn-default')) }}
                    @endif
                    @if (isset($delete_available) ? $delete_available : true)
                    {{ Form::submit('Delete', array('class' => 'btn btn-danger', 'name' => 'delete')) }}
                    @endif
                    
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">All items</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped tablesorter all">
                        
                        @yield('all_table')  
                        
                    </table>
                </div>
            </div>
        </div>
    </div><!-- /.row -->
</div>
@stop
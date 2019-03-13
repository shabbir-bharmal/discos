
@extends('admin.default')

@section('content')

<div class="row">    
    <div class="col-lg-12">
        <h1>Search bookings</h1>

        @include('admin.include.breadcrumb')
    </div>
</div>
<div class="row">

    @yield('multiple.delete.button')

    <div class="col-lg-12">
        
        @include('admin.include.notifications') 
        
        
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Bookings</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    @yield('table')
                </div>
            </div>
        </div>
    </div><!-- /.row -->
</div>
@stop


@section('scripts')
<script type="text/javascript">
    var objectType = 'booking';
</script>
@stop
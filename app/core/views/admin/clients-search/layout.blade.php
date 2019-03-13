
@extends('admin.default')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h1>Search clients</h1>

            @include('admin.include.breadcrumb')
        </div>
    </div>
    <div class="row">

        @yield('multiple.delete.button')

        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Clients</h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">

                        @if (!$errors->isEmpty())

                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                {{$errors->first('delete_client')}}
                            </div>

                        @endif

                        @yield('table')

                    </div>
                </div>
            </div>
        </div><!-- /.row -->
    </div>
@stop


@section('scripts')
    <script type="text/javascript">
        var objectType = 'client';
    </script>
@stop
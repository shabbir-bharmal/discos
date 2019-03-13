
@extends('admin.default')

@section('content')
<div class="row">    
    <div class="col-lg-12">
        <h1>All email templates</h1>

        @include('admin.include.breadcrumb')
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Templates</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    
                    <table class="table table-bordered table-hover table-striped tablesorter">

                        <thead>
                            <tr>
                                <th>Filename</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($emails as $email)
                            <tr data-id="{{$email->id}}">
                                <td>{{$email->name}}</td>
                                <td><a class="btn btn-primary btn-circle" title="Edit booking" href='{{ url("admin/emails/edit/$email->id") }}'><i class="fa fa-edit"></i></a></td>
                            </tr>                            
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">HTML</h3>
            </div>
            <div class="panel-body" id='html'>
                
            </div>
        </div>
    </div>
</div>
@stop


@section('scripts')
<script type="text/javascript">
    var objectType = 'email';
    $(document).ready(function(){
        $('table td').click(function(){
            
             $.ajax({
                    url: '/admin/emails/template/' + $(this).parent().data('id'),
                    success: function(response) {
                        var json_response = $.parseJSON(response);
                        $('#html').html(json_response.html);
                    }
             });
        });
    });
</script>
@stop
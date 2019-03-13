@extends('admin.clients-search.layout')

@section('multiple.delete.button')
    <div class="col-lg-12">
        <div class="panel pull-right">
            <div class="btn-group">
                <a id="deletePendingClients" href="javascript:void(0);" class="btn btn-warning pull-right">Delete Pending Clients</a>
            </div>
            <div class="btn-group">
                <a id="multiple_deletes" href="javascript:void(0);" class="btn btn-warning pull-right">Delete selected</a>
            </div>

            <div style="clear:both;"></div>
        </div>
    </div>
@stop
@section('table')

    {{ Form::open(['url' => 'admin/clients/multiple', 'id' => 'form']) }}
    {{ Form::hidden('_method', 'DELETE') }}

    <table class="table table-bordered table-hover table-striped tablesorter table-filter table-data-table">

        <thead>
        <tr>
            <th></th>
            <th>Name</th>
            <th>Email</th>
            <th>Telephone</th>
            <th>Postcode</th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        @foreach ($clients as $client)

            <tr data-id='{{$client->id}}'>
                <td><input type="checkbox" name="deletes[]" value="{{$client->id}}"/></td>
                <td>{{$client->name}}</td>
                <td>{{$client->email}}</td>
                <td>{{$client->telephone}}</td>
                <td>{{$client->postcode}}</td>
                <td><a class="btn btn-primary btn-circle" title="Edit client"
                       href='{{ url("admin/clients/edit/$client->id") }}'><i class="fa fa-edit"></i></a>
                    <a class="btn btn-danger btn-circle" title="Delete client"
                       href='{{ url("admin/clients/delete/$client->id") }}'><i
                                class="fa fa-times"></i></a></td>
            </tr>

        @endforeach

        </tbody>

        <tfoot>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Telephone</th>
            <th>Postcode</th>
        </tr>
        </tfoot>

    </table>

    {{ Form::close() }}
@stop

@section('scripts')
    <script type="text/javascript">
        var objectType = 'client';

        $('#multiple_deletes').on('click', function(e) {
            e.preventDefault();

            if (!confirm("Are you sure you want to delete multiple clients?")) return false;
            $('#form').submit();
        });

        $(document).ready(function() {
            $('#deletePendingClients').on('click', function (e) {
                var btn = $(this);
                btn.attr('disabled', true).text('Deleting, please wait...');

                e.preventDefault();

                if (!confirm('Are you sure you want to delete pending clients?')) return false;

                $.ajax({
                    url: 'https://www.discos.uk/admin/client/delete/pending',
                    method: 'get',
                    dataType: 'json'

                }).done(function (ret) {
                    alert(ret.msg);
                    window.location = ret.location;

                });
            });
        });

    </script>
@stop
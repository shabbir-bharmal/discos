@extends('admin.clients-search.layout')

@section('table')

    <table class="table table-bordered table-hover table-striped tablesorter table-filter table-data-table">

        <thead>
        <tr>
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
                <td>{{$client->name}}</td>
                <td>{{$client->email}}</td>
                <td>{{$client->telephone}}</td>
                <td>{{$client->postcode}}</td>
                <td><a class="btn btn-primary btn-circle" title="Edit client"
                       href='{{ url("admin/clients/edit/$client->id") }}'><i class="fa fa-edit"></i></a>
                    <a class="btn btn-danger btn-circle" title="Delete client"
                       href='{{ url("admin/clients/delete/$client->id") }}'><i class="fa fa-times"></i></a></td>
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
    </div>
@stop


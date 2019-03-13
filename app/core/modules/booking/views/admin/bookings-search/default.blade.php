
@extends('admin.bookings-search.layout')

@section('table')
    <table class="table table-bordered table-hover table-striped tablesorter table-filter table-data-table">

        <thead>
        <tr>
            <th>Package</th>
            <th>Date</th>
            <th>Venue</th>
            <th>Client name</th>
            <th>Client email</th>
            <th>Status</th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        @foreach ($bookings as $booking)

            <tr data-id='{{$booking->id}}'>
                <td>{{$booking->package->name}}</td>
                <td data-order="{{$booking->date_timestamp}}">{{$booking->date}}</td>
                <td>{{$booking->venue_name}}</td>
                @if($booking->client()->count() > 0)
                <td>{{$booking->client->name}}</td>
                <td>{{$booking->client->email}}</td>
                @else
                <td></td>
                <td></td>
                @endif
                <td>{{$booking->status}}</td>
                <td>
                    <a class="btn btn-primary btn-circle" title="Edit booking" href='{{ url("admin/bookings/edit/$booking->id") }}'><i class="fa fa-edit"></i></a>
                    <a class="btn btn-success btn-circle" title="Duplicate booking" href='{{ url("admin/bookings/duplicate/$booking->id") }}'><i class="fa fa-copy"></i></a>
                    <a class="btn btn-info btn-circle" title="Send contract" href='{{ url("admin/bookings/send-contract/$booking->id") }}'><i class="fa fa-file-o"></i></a>
                    @if ($booking->followUp)
                        <a class="btn btn-warning btn-circle" title="Delete FollowUp" href='{{ url("admin/bookings/delete-followup/$booking->id") }}'><i class="fa fa-times"></i></a>
                    @endif
                    <a class="btn btn-danger btn-circle" title="Delete booking" href='{{ url("admin/bookings/delete/$booking->id") }}'><i class="fa fa-times"></i></a>
                </td>
            </tr>

        @endforeach

        </tbody>

        <tfoot>
        <tr>
            <th>Package</th>
            <th>Date</th>
            <th>Venue</th>
            <th>Client name</th>
            <th>Client email</th>
            <th>Status</th>
        </tr>
        </tfoot>

    </table>
@stop

@section('scripts')
    <script type="text/javascript">
        var objectType = 'booking';
        var table_order = [[2, "desc"]];

    </script>
@stop
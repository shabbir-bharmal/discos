@extends('admin.bookings-search.layout')

@section('multiple.delete.button')
    <div class="col-lg-12">
        <div class="panel pull-right">
            <div class="btn-group">
                <a id="deletePendingBookings" href="javascript:void(0);" class="btn btn-warning pull-right">Delete Pending Bookings</a>
            </div>
            <div class="btn-group">
                <a id="multiple_deletes" href="javascript:void(0);" class="btn btn-warning pull-right">Delete selected</a>
            </div>

            <div style="clear:both;"></div>
        </div>
    </div>
@stop

@section('table')

    {{ Form::open(['url' => 'admin/bookings/multiple', 'id' => 'form']) }}
    {{ Form::hidden('_method', 'DELETE') }}


    <table class="table table-bordered table-hover table-striped tablesorter table-filter table-data-table">

        <thead>
        <tr>
            <th></th>
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
                <td><input type="checkbox" name="deletes[]" value="{{$booking->id}}"/></td>
                <td>{{$booking->package->name}}</td>
                <td data-order="{{$booking->date_timestamp}}">{{$booking->date}}</td>
                <td>{{$booking->venue_name}}</td>
                <td>{{$booking->client->name or 'N/A'}}</td>
                <td>{{$booking->client->email or 'N/A'}}</td>
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

    {{ Form::close() }}
@stop

@section('scripts')
    <script type="text/javascript">
        var objectType = 'booking';
        var table_order = [[2, "desc"]];

        $('#multiple_deletes').on('click', function(e) {
            e.preventDefault();

            if (!confirm("Are you sure you want to delete multiple bookings?")) return false;
            $('#form').submit();
        });

        $(document).ready(function() {
            $('#deletePendingBookings').on('click', function (e) {
                var btn = $(this);
                btn.attr('disabled', true).text('Deleting, please wait...');

                e.preventDefault();

                if (!confirm('Are you sure you want to delete pending bookings?')) return false;

                $.ajax({
                    url: 'https://www.discos.uk/admin/booking/delete/pending',
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
<h1>DJ Nick Burrett - Bookings List</h1>

<table width="100%" border="1" cellspacing="2" cellpadding="2" align="center">
    <tbody>
        <tr>
            <td>Date</td>
            <td>Staff</td>
            <td>Venue</td>
            <td>Client</td>
            <td>Occasion</td>
        </tr>
    @foreach ($data['future_bookings'] as $future_booking)
    <tr>
        <td nowrap><div>{{ date("D d-m-Y",strtotime($future_booking->date)) }}</div>
        <div>{{substr($future_booking->start_time, 0, -3)}} - {{substr($future_booking->finish_time, 0, -3)}}</div></td>
        <td>{{$future_booking->staff}}</td>
        <td><div>{{$future_booking->venue_name}}</div>
        <div>{{$future_booking->venue_postcode}}</div></td>
        <td><?php echo (isset($future_booking->client) && !empty($future_booking->client))?$future_booking->client->name:""; ?></td>
        <td>{{$future_booking->occasion}}</td>
    </tr>
    @endforeach
    </tbody>
</table>
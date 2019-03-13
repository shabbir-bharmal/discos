<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>DJ Nick Burrett - New Booking Added</title>
</head>
<body>

<h1>DJ Nick Burrett - New Booking Added</h1>

<p>For full details <a href="https://www.discos.uk/admin?login_token=$2y$10$ewpZ3jsGr7tPLc0fcKKBPeWCyPERFSzFI7gVVnttocWMuu8cSdSxW">click here</a>.</p>

<table width="100%" border="1" cellspacing="2" cellpadding="2">
    <tbody>
        <tr>
            <td>Client Name</td>
            <td>{{$data->client->name}}</td>
        </tr>
        <tr>
            <td>Date</td>
            <td>{{date("l jS \of F Y",strtotime($data->date))}}</td>
        </tr>
        <tr>
            <td>Times</td>
            <td>{{substr($data->start_time, 0, -3)}} - {{substr($data->finish_time, 0, -3)}}</td>
        </tr>
        <tr>
            <td>Venue</td>
            <td>
                <div>{{$data->venue_name}}</div>
                <div>{{$data->venue_address1}}</div>
                <div>{{$data->venue_address2}}</div>
                <div>{{$data->venue_address3}}</div>
                <div>{{$data->venue_postcode}}</div>
            </td>
        </tr>
        <tr>
            <td>Occasion</td>
            <td>{{$data->occasion}}</td>
        </tr>
        <tr>
            <td>Fee</td>
            <td>£{{$data->total_cost}}</td>
        </tr>
    </tbody>
</table>

</body>
</html>
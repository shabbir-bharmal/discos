

@extends('admin.default')

@section('content')

<div class="row">    
    <div class="col-lg-12">
        <h1>Calendar</h1>

        @include('admin.include.breadcrumb')
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div id="calendar"></div>
    </div><!-- /.row -->
</div>
@stop

@section('scripts')
<script type="text/javascript">
    var objectType = 'booking';
    $(document).ready(function() {
        $('#calendar').fullCalendar(
        {
            header: {
                left: 'title',
                center: '',
                right: 'prev,next'
            },
            dayClick: function(date, allDay, jsEvent, view) {
                /*if (date >= new Date()) {
                    var formattedDate = date.getDate() + "-" + (date.getMonth() + 1) + "-" + date.getFullYear();
                    window.open('?step=selection&date=' + formattedDate, "_self");
                } else {
                    alert("That date is in the past.");
                }*/
            },
            events: {
                url: '/admin/calendar-bookings',
                error: function() {
                    alert('error');
                }
            }
        });
    });
</script>
<script type="text/javascript" src="/assets/js/fullcalendar.js"></script>

@stop
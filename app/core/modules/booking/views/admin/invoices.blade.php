@extends('admin.default')
@section('content')

<div class="row">    
    <div class="col-lg-12">
        <h1>Invoices</h1>

        @include('admin.include.breadcrumb')
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        
        @include('admin.include.notifications')

        <!-- <ipp:connectToIntuit></ipp:connectToIntuit> -->

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Send Invoice</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    {{ Form::open(array('url' => 'admin/invoices', 'method' => 'post', 'id' => 'form-submit')) }}
                    <div class="col-lg-6">

                        <div class='form-group'>
                            {{ Form::label('client', 'Client') }}
                            {{ Form::text('client', '', array('class' => 'form-control', 'placeholder' => 'Client name')) }}
                        </div>
                        
                    </div>
                    <div class="col-lg-6">
                        
                        <div class='form-group'>
                            {{ Form::label('', 'Bookings') }}
                            <div id="bookings"></div>
                        </div>

                    </div>
                    <div class="col-lg-12">
                        
                        <hr/>

                        <div class='form-group text-right'>
                            <?php echo Form::reset('Clear Form', array('class' => 'btn btn-default')); ?>
                            <?php echo Form::submit('Send Invoice(s)', array('class' => 'btn btn-primary')); ?>
                        </div>

                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>

@stop
@section('scripts')

<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://appcenter.intuit.com/Content/IA/intuit.ipp.anywhere-1.3.3.js"></script>
<script type="text/javascript">
    var objectType = 'invoice';

    var clients = [
<?php
$cli = [];
foreach ($clients as $client) {
    $cli[] = $client->name;
}

echo '"' . implode('","', $cli) . '"';
?>
    ];


    intuit.ipp.anywhere.setup({
        grantUrl: '{{ Invoicing::callback_url() }}?start=t',
        datasources: {
            quickbooks : true
        }
    });

    $(document).ready(function() {

        $("#client").autocomplete({
            source: clients,
            select: function(e,u) {
                populateBookingsList(u.item.value);
            }
        });

        // on off focus of client, populate with booking data
        /*$("#client").focusout(function() {
            populateBookingsList($(this).val());
        });*/
        
        $("#client").keyup(function() {
            $('#bookings').html('');
        });

        populateBookingsList($('#client').val());
    });

    function populateBookingsList(client_name) {
        
        $('#bookings').html('');
        
        if (client_name == '') return false;
        
        // ajax call to get bookings in past for client
        $.get('/ajax/bookings?client_name='+client_name)
        .done(function(response) {
            var data = $.parseJSON(response);
            if(data.bookings) {
                
                if (data.bookings.length == 0) {
                    
                    $('#bookings').append('<p>No bookings found</p>');
                    
                } else {                
                    
                    $.each(data.bookings, function(index, element) {
                        
                        $('#bookings').append('<label><input type="checkbox" name="bookings[]" value="'+element.id+'" />&nbsp;'+element.date+'&nbsp;('+element.venue_name+')</label><br/>');

                    });
                }
            } else {
                console.log(data.error);
            }
        });
    }

</script>

@stop
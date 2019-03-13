

@extends('admin.default')


@section('content')
<div class="row">    
    <div class="col-lg-12">
        <h1>Add booking</h1>

        @include('admin.include.breadcrumb')
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary" id='update-form'>
            <div class="panel-heading">
                <h3 class="panel-title">Add</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-6">
                        <?php echo Form::open(array('url' => 'admin/bookings/add', 'method' => 'post', 'id' => 'form-submit')); ?>

                        <div class='form-group'>
                            <?php
                            echo Form::label('client', 'Client');
                            echo Form::text('client', '', array('class' => 'form-control', 'placeholder' => 'Client name'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('package_id', 'Package');
                            echo Form::select('package_id', $packages, '', array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class="form-group">
                            <?php
                            echo Form::label('set_id', 'Package');
                            echo Form::select('set_id', $sets, '', array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('date', 'Date');
                            echo Form::text('date', '', array('class' => 'form-control datepicker'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('date_booked', 'Date booking made');
                            echo Form::text('date_booked', '', array('class' => 'form-control datepicker', 'data-date-format' => 'dd-mm-yyyy'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('start_time', 'Start time');
                            echo Form::text('start_time', '', array('class' => 'form-control timepicker'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('finish_time', 'Finish time');
                            echo Form::text('finish_time', '', array('class' => 'form-control timepicker'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('venue_name', 'Venue name');
                            echo Form::text('venue_name', '', array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('venue_address1', 'Venue Street');
                            echo Form::text('venue_address1', '', array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('venue_address2', 'Venue Town');
                            echo Form::text('venue_address2', '', array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('venue_address3', 'Venue County');
                            echo Form::text('venue_address3', '', array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('venue_postcode', 'Venue postcode');
                            echo Form::text('venue_postcode', '', array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('event_occasion', 'Event Type');
                            echo Form::select('event_occasion', $occasions, '', array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('occasion', 'Occasion');
                            echo Form::text('occasion', '', array('class' => 'form-control'));
                            ?>
                        </div>
                        @foreach ($further_details  as $occasion => $occasion_details)
                        <div id="{{$occasion}}" class="further_details">
                        
                            @foreach ($occasion_details  as $key => $label)

                            <div class='form-group'>
                                <?php
                                echo Form::label($key, $label);
                                echo Form::text($key, '', array('class' => 'form-control'));
                                ?>
                            </div>

                            @endforeach
                        </div>
                        @endforeach

                        <h4>Extras</h4>
                        @foreach ($extras  as $id => $name)
                            <label style="font-weight: normal;">
                                {{ Form::checkbox('extras[]', $id) }}
                                {{$name}}
                            </label><br/>
                        @endforeach
                       
                    </div>
                    <div class="col-lg-6">
                        <div class='form-group'>
                            <?php
                            echo Form::label('deposit_requested', 'Deposit amount due');
                            echo Form::text('deposit_requested', '', array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('deposit_paid', 'Deposit paid');
                            echo Form::text('deposit_paid', '', array('class' => 'form-control datepicker'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('deposit_amount', 'Deposit amount paid');
                            echo Form::text('deposit_amount', '', array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('deposit_payment_method', 'Deposit payment method');
                            echo Form::text('deposit_payment_method', '', array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('balance_requested', 'Balance amount due');
                            echo Form::text('balance_requested', '', array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('balance_paid', 'Balance paid');
                            echo Form::text('balance_paid', '', array('class' => 'form-control datepicker'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('balance_amount', 'Balance amount paid');
                            echo Form::text('balance_amount', '', array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('balance_payment_method', 'Balance payment method');
                            echo Form::text('balance_payment_method', '', array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('total_cost', 'Total cost');
                            echo Form::text('total_cost', '', array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('setup_equipment_time', 'Setup equipment time (mins)');
                            echo Form::text('setup_equipment_time', '', array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('staff', 'Staff');
                            echo Form::text('staff', '', array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('ref_no', 'Reference number');
                            echo Form::text('ref_no', '', array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('status', 'Status');
                            echo Form::select('status', $statuses, '', array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('notes', 'Notes');
                            echo Form::textarea('notes', '', array('class' => 'form-control'));
                            ?>
                        </div>

                        <div class='form-group'>
                            <?php echo Form::submit('Add', array('class' => 'btn btn-primary')); ?>
                            <?php echo Form::reset('Clear', array('class' => 'btn btn-default')); ?>
                            <?php echo Form::close() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop
@section('scripts')

<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<script type="text/javascript">
    var objectType = 'booking';
    
    var clients = [
        <?php 

        foreach($clients as $client){
            $cli[] = $client->name;
        }

        echo '"' . implode('","', $cli) . '"';

        ?>
        ];
    
    $(document).ready(function(){
        
        $('#event_occasion').change(function(){
            showFurtherDetails();
        });
        
        showFurtherDetails();        
        
        $( "#client" ).autocomplete({source: clients});
    });
</script>

@stop
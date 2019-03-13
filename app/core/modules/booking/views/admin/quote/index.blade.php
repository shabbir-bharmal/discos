@extends('admin.default')
@section('content')

<div class="row">    
    <div class="col-lg-12">
        <h1>New quote</h1>

        @include('admin.include.breadcrumb')
    </div>
</div>

@if ($errors->has('availability'))
    <div class="alert alert-danger">{{ $errors->first('availability') }}</div>

    <div class="panel panel-danger">
        <div class="panel-heading">There is NO Availability</div>
        <div class="panel-body">
            <p>We have the following bookings on {{ Input::old('date') }}</p>
                <table class="table table-hover">
                    @foreach (Session::get('events') as $event)
                        <tr>
                            <td>{{ $event->start_time }}</td>
                            <td>{{ $event->finish_time }}</td>
                            <td>{{ $event->venue_postcode }}</td>
                            <td>{{ $event->occasion }}</td>
                            <td>{{ $event->equipmentSet->name }}</td>
                        </tr>
                    @endforeach
                </table>
        </div>
    </div>
@endif
<div class="row">    
    <div class="panel panel-info margin-top-10">
        <div class="panel-heading">Step 1 - Event details</div>
        <div class="panel-body">
            <div class="col-lg-6">
                {{ Form::open(['url' => 'admin/quote/step1']) }}
                <div class="form-group">
                    {{ Form::label('package_name', 'Choose a package') }}
                    {{ Form::select('package_name', $packages, Input::old('package_name'), ['class' => 'form-control']) }}
                    <p class="text-danger">{{$errors->first('package_name')}}</p>
                </div>
                <div class="form-group">
                    {{ Form::label('occasion', 'Occasion') }}
                    {{ Form::text('occasion', Input::old('occasion'), ['class' => 'form-control']) }}
                    <p class="text-danger">{{$errors->first('occasion')}}</p>
                </div>
                <div class="form-group">
                    {{ Form::label('date', 'Date of your event') }}
                    {{ Form::text('date', Input::old('date'), ['class' => 'form-control datepicker']) }}
                    <p class="text-danger">{{$errors->first('date')}}</p>
                </div>
                <div class="form-group">
                    <!-- <span class="help-block">Timings: Please suggest 7pm until Midnight for adults or 11am until 1pm or 3pm until 5pm for kids</span> -->
                    {{ Form::label('start_time', 'Start Time') }}
                    {{ Form::text('start_time', Input::old('start_time'), ['class' => 'form-control timepicker']) }}
                    <p class="text-danger">{{$errors->first('start_time')}}</p>
                </div>
                <div class="form-group">
                    {{ Form::label('finish_time', 'Finish Time') }}
                    {{ Form::text('finish_time', Input::old('finish_time'), ['class' => 'form-control timepicker']) }}
                    <p class="text-danger">{{$errors->first('finish_time')}}</p>
                </div>
                <div class="form-group">
                    {{ Form::label('name', 'Name of the venue') }}
                    {{ Form::text('venue_name', Input::old('venue_name'), ['class' => 'form-control']) }}
                    <p class="text-danger">{{$errors->first('venue_name')}}</p>
                </div>
                <div class="form-group">
                    {{ Form::label('venue_postcode', 'Postcode of venue') }}
                    {{ Form::text('venue_postcode', Input::old('venue_postcode'), ['class' => 'form-control']) }}
                    <p class="text-danger">{{$errors->first('venue_postcode')}}</p>
                </div>
                <div class="form-group">
                    {{ Form::submit('Check availability', ['class' => 'btn btn-primary']) }}
                </div>
                {{ Form::close() }}
            </div>
            <div class="col-lg-6" >
                <div class="panel panel-warning margin-top-10">
                    <div class="panel-heading">Package Description</div>
                    <div class="panel-body" >
                    <div id="package_desc_body" ></div>
                   <!--  <p>The total cost of this package is &euro;<span id="total_price"></span></p>
                    <hr>
                    <p>The deposit due to make a provisional booking is &euro;<span id="deposit_price"></span>. The balance of &euro;<span id="remaining_price"></span> is due on the day.</p> -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script type="text/javascript">

    $(document).ready(function() {

        $("#package_name").on("change", function(){
            $.ajax({url: "https://www.discos.uk/admin/quote/get-package/"+ $("#package_name").val(), success: function(result){
               
                $("#package_desc_body").html(result.description);
                /*$("#total_price").text(result.min_price);
                $("#deposit_price").text(result.deposit);
                $("#remaining_price").text(parseInt(result.min_price)- parseInt(result.deposit));*/
            }});
        });

        if ($('.datepicker').length > 0) {
            $('.datepicker').datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd-mm-yy'

            });
        }

        $('.timepicker').timepicker();

        $.ajax({url: "https://www.discos.uk/admin/quote/get-package/"+ $("#package_name").val(), success: function(result){
                
                $("#package_desc_body").html(result.description);
                /*$("#total_price").text(result.min_price);
                $("#deposit_price").text(result.deposit);
                $("#remaining_price").text(parseInt(result.min_price)- parseInt(result.deposit));*/
            }});

    });
</script>
@endsection
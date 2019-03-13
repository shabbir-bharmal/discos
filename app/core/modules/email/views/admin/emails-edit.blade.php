

@extends('admin.default')

@section('content')
<div class="row">    
    <div class="col-lg-12">
        <h1>Edit email template</h1>

        @include('admin.include.breadcrumb')
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary" id='update-form'>
            <div class="panel-heading">
                <h3 class="panel-title">Add / Edit item</h3>
            </div>
            <div class="panel-body">
                <?php echo Form::open(array('url' => 'admin/emails/edit', 'method' => 'post', 'id' => 'form-submit')); ?>
                <div class="row">
                    <div class="col-lg-4">
                        <input type="hidden"  name="id" value="{{$email->id}}" />
                        <div class='form-group'>
                            <?php
                            echo Form::label('name', 'Name');
                            echo Form::text('name', $email->name, array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('type', 'Type');
                            echo Form::select('type', EmailTemplate::get_type_selection(), $email->type, array('class' => 'form-control'));
                            ?>
                        </div>
                        
                        <div id='regular-options'>
                            <div class='form-group'>
                                <?php
                                echo Form::label('recipient', 'Recipient');
                                echo Form::select('recipient', EmailTemplate::get_recipient_selection(), $email->recipient, array('class' => 'form-control'));
                                ?>
                            </div>
                            <div class='form-group'>
                                <?php
                                echo Form::label('filter', 'When to send');
                                echo Form::select('filter', EmailTemplate::get_filter_selection(), $email->filter, array('class' => 'form-control'));
                                ?>
                            </div>
                            <div class='form-group'>
                                <?php
                                echo Form::label('data', 'Email data');
                                echo Form::select('data', EmailTemplate::get_data_selection(), $email->data, array('class' => 'form-control'));
                                ?>
                            </div>
                        </div>                        
                        
                        <div class='form-group'>
                            <?php
                            echo Form::label('view', 'Filename');
                            echo Form::select('view', $views, $email->view, array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('subject', 'Subject');
                            echo Form::text('subject', $email->subject, array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('email_from', 'From (email)');
                            echo Form::text('email_from', $email->email_from, array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('name_from', 'From (name)');
                            echo Form::text('name_from', $email->name_from, array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('cc', 'Bcc');
                            echo Form::text('cc', $email->cc, array('class' => 'form-control'));
                            ?>
                        </div>

                        <div class='form-group'>
                            <?php
                            echo Form::label('reply_to', 'Reply To');
                            echo Form::text('reply_to', $email->reply_to, array('class' => 'form-control'));
                            ?>
                        </div>
                            
                        <h4 class="no-quotation">Packages</h4>
                        {{ Form::hidden('packages[]', 0) }}
                        
                        @foreach (Package::all() as $package)

                        <label class="no-quotation" style="font-weight: normal;">
                            {{ Form::checkbox('packages[]', $package->id, in_array($package->id, $email->packages)) }}
                            {{$package->getFullDescription()}}
                        </label><br/>

                        @endforeach
                    </div>
                    <div class="col-lg-8">
                        <div class='form-group'>
                            <?php
                            echo Form::label('from', 'Body');
                            echo Form::textarea('html', $email->html, array('class' => 'form-control'));
                            ?>
                        </div>

                        <div class='form-group'>
                            <?php echo Form::submit('Save', array('class' => 'btn btn-primary')); ?>
                            <?php echo Form::reset('Clear', array('class' => 'btn btn-default')); ?>
                            <?php echo Form::submit('Delete', array('class' => 'btn btn-danger', 'name' => 'delete')); ?>
                        </div>
                    </div>
                </div>
                <?php echo Form::close() ?>
            </div>
        </div>
    </div>
</div>


@stop
@section('scripts')
<script type="text/javascript">
    var objectType = 'booking';

    $(document).ready(function() {

        $('#event_occasion').change(function() {
            showFurtherDetails();
        });

        $('#type').change(function() {
            if($(this).val() == 'quotation'){
                $(".no-quotation").hide();
            } else {
                $(".no-quotation").show();
            }
        });

        if($('#type').val() == 'quotation'){
                $(".no-quotation").hide();
            } else {
                $(".no-quotation").show();
            }

        showFurtherDetails();
    });
</script>
@stop
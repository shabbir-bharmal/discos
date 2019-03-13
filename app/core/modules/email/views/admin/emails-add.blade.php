

@extends('admin.default')


@section('content')
<div class="row">    
    <div class="col-lg-12">
        <h1>Add email template</h1>

        @include('admin.include.breadcrumb')
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="panel panel-primary" id='update-form'>
            <div class="panel-heading">
                <h3 class="panel-title">Add item</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <?php echo Form::open(array('url' => 'admin/emails/add', 'method' => 'post', 'id' => 'form-submit')); ?>
                        <div class='form-group'>
                            <?php
                            echo Form::label('name', 'Name');
                            echo Form::text('name', \Input::old('name'), array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('type', 'Type');
                            echo Form::select('type', EmailTemplate::get_type_selection(), \Input::old('type'), array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('view', 'Filename');
                            echo Form::select('view', $views, \Input::old('view'), array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('subject', 'Subject');
                            echo Form::text('subject', \Input::old('subject'), array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('email_from', 'From (email)');
                            echo Form::text('email_from', \Input::old('email_from'), array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('name_from', 'From (name)');
                            echo Form::text('name_from', \Input::old('name_from'), array('class' => 'form-control'));
                            ?>
                        </div>

                        <div class='form-group'>
                            <?php echo Form::submit('Save', array('class' => 'btn btn-primary')); ?>
                            <?php echo Form::reset('Clear', array('class' => 'btn btn-default')); ?>
                            <?php echo Form::submit('Delete', array('class' => 'btn btn-danger', 'name' => 'delete')); ?>
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
<script type="text/javascript">
    var objectType = 'booking';
    
    $(document).ready(function(){
        
        $('#event_occasion').change(function(){
            showFurtherDetails();
        });
        
        showFurtherDetails();
    });
</script>
@stop
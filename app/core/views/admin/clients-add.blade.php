

@extends('admin.default')


@section('content')
<div class="row">    
    <div class="col-lg-12">
        <h1>Add client</h1>

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
                    <div class="col-lg-12">
                        <?php echo Form::open(array('url' => 'admin/clients/client', 'method' => 'post', 'id' => 'form-submit')); ?>
                        <input type='hidden' name='id' />
                        <div class='form-group'>
                            <?php
                            echo Form::label('name', 'Name');
                            echo Form::text('name', '', array('class' => 'form-control', 'placeholder' => 'Joe Dunne'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('email', 'Email');
                            echo Form::text('email', '', array('class' => 'form-control', 'placeholder' => 'example@gmail.com'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('telephone', 'Home Tel');
                            echo Form::text('telephone', '', array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('mobile', 'Mobile Tel');
                            echo Form::text('mobile', '', array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('address1', 'House No / Street Name');
                            echo Form::text('address1', '', array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('address2', 'District');
                            echo Form::text('address2', '', array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('address3', 'Town');
                            echo Form::text('address3', '', array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('address4', 'County');
                            echo Form::text('address4', '', array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('postcode', 'Postcode');
                            echo Form::text('postcode', '', array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('heard_about', 'Where did you hear about us?');
                            echo Form::text('heard_about', '', array('class' => 'form-control'));
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
<script type="text/javascript">
    var objectType = 'client';
    
    $(document).ready(function(){
        
        $('#event_occasion').change(function(){
            showFurtherDetails();
        });
        
        showFurtherDetails();
    });
</script>

@stop
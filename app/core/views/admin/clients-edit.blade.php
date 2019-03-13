

@extends('admin.default')


@section('content')
<div class="row">    
    <div class="col-lg-12">
        <h1>Edit client</h1>

        @include('admin.include.breadcrumb')
    </div>
</div>
<div class="row">
    <div class="col-lg-12">

        @include('admin.include.notifications')        
        
        <div class="panel panel-primary" id='update-form'>
            <div class="panel-heading">
                <h3 class="panel-title">Add / Edit item</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        
                        <?php echo Form::open(array('url' => 'admin/clients/client', 'method' => 'post', 'id' => 'form-submit')); ?>
                        <input type='hidden' name='id' value="<?=$client->id?>"/>
                        <div class='form-group'>
                            <?php
                            echo Form::label('name', 'Name');
                            echo Form::text('name', $client->name, array('class' => 'form-control', 'placeholder' => 'Joe Dunne'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('email', 'Email');
                            echo Form::text('email', $client->email, array('class' => 'form-control', 'placeholder' => 'example@gmail.com'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('telephone', 'Home Tel');
                            echo Form::text('telephone', $client->telephone, array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('mobile', 'Mobile Tel');
                            echo Form::text('mobile', $client->mobile, array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('address1', 'House No / Street Name');
                            echo Form::text('address1', $client->address1, array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('address2', 'District');
                            echo Form::text('address2', $client->address2, array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('address3', 'Town');
                            echo Form::text('address3', $client->address3, array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('address4', 'County');
                            echo Form::text('address4', $client->address4, array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('postcode', 'Postcode');
                            echo Form::text('postcode', $client->postcode, array('class' => 'form-control'));
                            ?>
                        </div>
                        <div class='form-group'>
                            <?php
                            echo Form::label('heard_about', 'Where did you hear about us?');
                            echo Form::text('heard_about', $client->heard_about, array('class' => 'form-control'));
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
    var objectType = 'client';
    
    $(document).ready(function(){
        
        $('#event_occasion').change(function(){
            showFurtherDetails();
        });
        
        showFurtherDetails();
    });
</script>
@stop
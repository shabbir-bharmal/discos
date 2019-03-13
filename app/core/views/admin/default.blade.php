<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{$tag}}</title>

    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- Page-Level Plugin CSS - Dashboard -->
    <link href="/assets/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
    <link href="/assets/css/plugins/timeline/timeline.css" rel="stylesheet">
    <link href="/assets/css/jquery-ui-1.10.3.custom.css"  rel="stylesheet">
    <link href="/assets/css/jquery.ui.timepicker.css"  rel="stylesheet">
    <link href="/assets/css/bootstrap-2.3.0.extra.css"  rel="stylesheet">
    <style>

        /*
         * Timepicker stylesheet
         * Highly inspired from datepicker
         * FG - Nov 2010 - Web3R 
         *
         * version 0.0.3 : Fixed some settings, more dynamic
         * version 0.0.4 : Removed width:100% on tables
         * version 0.1.1 : set width 0 on tables to fix an ie6 bug
         */

        .ui-timepicker-inline { display: inline; }

        #ui-timepicker-div { padding: 0.2em }
        .ui-timepicker-table { display: inline-table; width: 0; }
        .ui-timepicker-table table { margin:0.15em 0 0 0; border-collapse: collapse; }

        .ui-timepicker-hours, .ui-timepicker-minutes { padding: 0.2em;  }

        .ui-timepicker-table .ui-timepicker-title { line-height: 1.8em; text-align: center; }
        .ui-timepicker-table td { padding: 0.1em; width: 2.2em; }
        .ui-timepicker-table th.periods { padding: 0.1em; width: 2.2em; }

        /* span for disabled cells */
        .ui-timepicker-table td span {
                display:block;
            padding:0.2em 0.3em 0.2em 0.5em;
            width: 1.2em;

            text-align:right;
            text-decoration:none;
        }
        /* anchors for clickable cells */
        .ui-timepicker-table td a {
            display:block;
            padding:0.2em 0.3em 0.2em 0.5em;
            width: 2.2em;

            text-align:right;
            text-decoration:none;
        }
    </style>

    <!-- SB Admin CSS - Include with every page -->
    <link href="/assets/css/sb-admin.css" rel="stylesheet">
    <link href="/assets/css/disco-admin.css" rel="stylesheet">
  </head>

  <body>

    <div id="wrapper">
        
      <!-- Sidebar -->
      @include('admin.include.nav')

      <div id="page-wrapper">

        @yield('content')

      </div><!-- /#page-wrapper -->

    </div><!-- /#wrapper -->

    <!-- Core Scripts - Include with every page -->
    <script src="/assets/js/jquery-1.10.2.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>

    <!-- Page-Level Plugin Scripts - Dashboard -->
    <script src="/assets/js/plugins/morris/raphael-2.1.0.min.js"></script>
    <script src="/assets/js/plugins/morris/morris.js"></script>
    <link rel="stylesheet" href="/assets/css/fullcalendar.css" type="text/css" media="screen" />
    <script src="/assets/js/jquery-ui-1.10.3.custom.min.js"></script>
    <script src="/assets/js/jquery.ui.timepicker.js"></script>
    <script src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.js"></script>
    <script src="/assets/js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script src="/vendor/ckeditor/ckeditor/ckeditor.js"></script>

    <!-- SB Admin Scripts - Include with every page -->
    <script src="/assets/js/sb-admin.js"></script>

    <script>
        var table_order = false;
    </script>
    
    @yield('scripts')
    
    <script type="text/javascript"> 
        
        function showFurtherDetails()
        {
            $('.further_details').hide();

            switch($('#event_occasion').val()) {
                case 'birthday':
                case 'wedding':
                    $('#'+$('#event_occasion').val()).show();
                    break;
            }
        }
        
        $(document).ready(function(){
            
            if($('.ckeditor').length > 0) {
                CKEDITOR.replace( 'ckeditor' );
            }

            var table;

            if (table_order === false) {
                table = $('table.table-data-table').DataTable();
            } else {
                table = $('table.table-data-table').DataTable({
                    order: table_order
                });
            }

            $("table.table-filter tfoot th").each( function ( i ) {
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(this).empty() )
                    .on( 'change', function () {
                        table.column( i )
                            .search( '^'+$(this).val()+'$', true, false )
                            .draw();
                } );

                table.column( i ).data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );

            if ($('.datepicker').length > 0) {
                 $('.datepicker').datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'dd-mm-yy'

                });
            }
            
            $('table.all tbody tr').click(function(){
                
                $('table.all tbody tr').removeClass('active');
                $(this).addClass('active');
                
                // fetch via ajax this item
                var id = $(this).data("id");                

                $.ajax({
                    url: '/admin/'+objectType+'s/'+objectType+'/' + id,
                    success: function(response) {
                        var reply = $.parseJSON(response);
                        
                        console.log(reply);
                        
                        $.each(reply, function(key, value){
                            var $ctrl = $('[name='+key+']', $('#update-form form'));  
                            switch($ctrl.attr("type"))  
                            {  
                                case "text" :   
                                case "hidden":  
                                $ctrl.val(value);   
                                break;   
                                case "radio" : case "checkbox":
                                $ctrl.each(function(){
                                   if(value == 1) { 
                                       $(this).attr("checked","checked"); 
                                   } else {
                                       $(this).attr("checked",false); 
                                   }});   
                                break;  
                                case "password":
                                $ctrl.val("");   
                                break;
                                default:
                                $ctrl.val(value); 
                            } 
                            
                            if (typeof value == 'object' && value != null) {
                                $.each(value, function(index, val) {
                                    $ctrl = $('[name="'+key+'[]"][value='+val+']', $('#update-form form')).attr("checked","checked");
                                });
                            }
                        });

                        
                        if ($('input[type="password"]').length > 0) {
                            $('input[type="password"]').val("");
                        }
                        
                        if (objectType == 'schedule') {
                            set_regularity_fields();
                        }
                    }
                });
            });
            
            if($('.timepicker').length > 0) {
                $('.timepicker').timepicker();
            }
            
            if($('.btn-danger').length > 0)
            {
                $('input.btn-danger').click(function(e){
                    e.preventDefault();
                    if(confirm('Are you sure you want to delete this?')) {
                        
                        var $form = $('form#form-submit');
                        
                        $form.append('<input type="hidden" name="delete" value="Delete" />').submit();
                    }
                });
                
                $('a.btn-danger').click(function(e){
                    e.preventDefault();
                    if(confirm('Are you sure you want to delete this?')) {
                        
                        window.location.href = $(this).attr('href');
                    }
                });
            }
            
            if ($('.add-new').length > 0) {
                $('.add-new').click(function(){
                    $('form input[type="text"],form input[type="password"],form input[type="hidden"],form select').val("");
                    
                    // uncheck checkboxes
                    $('form input[type="checkbox"]').attr("checked", false);
                });
            }
            
        });
    </script>

  </body>
</html>

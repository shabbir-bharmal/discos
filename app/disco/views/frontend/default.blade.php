<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>{{$tag}}</title>

        <link href="/assets/css/bootstrap.min.css" rel="stylesheet">

        <!-- Page-Level Plugin CSS - Dashboard -->
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

        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    @yield('content')
                </div>
            </div>
        </div>        

        <!-- Core Scripts - Include with every page -->
        <script src="/assets/js/jquery-1.10.2.js"></script>
        <script src="/assets/js/bootstrap.min.js"></script>

        <!-- Page-Level Plugin Scripts - Dashboard -->
        <link rel="stylesheet" href="/assets/css/fullcalendar.css" type="text/css" media="screen" />
        <script src="/assets/js/jquery-ui-1.10.3.custom.min.js"></script>
        <script src="/assets/js/jquery.ui.timepicker.js"></script>

        @yield('scripts')

    </body>
</html>

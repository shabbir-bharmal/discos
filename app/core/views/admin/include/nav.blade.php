<!-- Sidebar -->
<nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/admin">{{Config::get('app.site_name')}}</a>
    </div>
    <ul class="nav navbar-top-links navbar-right">
        <li>
            <a href="/logout"><i class="fa fa-power-off"></i> Log Out</a>
        </li>
    </ul>
    <div class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                @if(Auth::user()->can('view.dashboard'))
                    <li><a href="{{ url('admin') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li> @endif
                @if(Auth::user()->can('manage.bookings'))
                    <li>
                        <a href="#"><i class="fa fa-calendar"></i> Bookings<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="{{ url('admin/bookings') }}"> Search bookings</a></li>
                            <li><a href="{{ url('admin/bookings/calendar') }}"> Calendar</a></li>
                            <li><a href="{{ url('admin/bookings/add') }}"> Add booking</a></li>
                        </ul>
                    </li>
                @endif
                @if(Auth::user()->can('manage.bookings'))
                    <li><a href="{{ url('admin/quote') }}"><i class="fa fa-gbp"></i> Quote</a></li>
                @endif
                @if(Auth::user()->can('manage.clients'))
                    <li><a href="{{ url('admin/clients') }}"><i class="fa fa-users"></i> Clients<span
                                    class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="{{ url('admin/clients/add') }}"> Add client</a></li>
                            <li><a href="{{ url('admin/clients/search') }}"> Search clients</a></li>
                        </ul>
                    </li>
                @endif
                @if(Auth::user()->can('manage.packages'))
                    <li>
                        <a href="#"><i class="fa fa-music"></i> Packages<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="{{ url('admin/packages') }}"> All packages</a></li>
                            <li><a href="{{ url('admin/packages/rules') }}"> Package rules</a></li>
                        </ul>
                    </li>
                @endif
                @if(Auth::user()->can('manage.packages'))
                    <li><a href="{{ url('admin/sets') }}"><i class="fa fa-music"></i> Equipment sets</a></li>
                @endif
                @if(Auth::user()->can('manage.extras'))
                    <li><a href="{{ url('admin/extras') }}"><i class="fa fa-plus-circle"></i> Additional extras</a></li>
                @endif
                @if(Auth::user()->can('manage.invoices'))
                    <li><a href="{{ url('admin/invoices') }}"><i class="fa fa-money"></i> Invoices</a></li>
                @endif
                @if(Auth::user()->can('manage.emails'))
                    <li>
                        <a href="#"><i class="fa fa-envelope"></i> Emails<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="{{ url('admin/emails') }}"> All templates</a></li>
                            <li><a href="{{ url('admin/emails/add') }}"> Add template</a></li>
                            <li><a href="{{ url('admin/schedules') }}"> Scheduler</a></li>
                            <li><a href="{{ url('admin/email-offers') }}"> Offer emails</a></li>
                        </ul>
                    </li>
                @endif
                @if(Auth::user()->can('manage.exports'))
                    <li><a href="{{ url('admin/export') }}"><i class="fa fa-download"></i> Exports</a></li>
                @endif
                @if(Auth::user()->can('manage.users'))
                    <li><a href="{{ url('admin/users') }}"><i class="fa fa-user"></i> Users</a></li>
                @endif
                @if(Auth::user()->can('manage.settings'))
                    <li><a href="{{ url('admin/settings') }}"><i class="fa fa-wrench"></i> Settings</a></li>
                @endif
				<!--stripe payment -->
                    <li><a href="{{ url('admin/payment') }}"><i class="fa fa-money"></i> Payment</a></li>			
				</ul>

        </div>
</nav>
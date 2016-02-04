<!DOCTYPE html>
<html>
    <head>
	@section('head')

        <meta charset="UTF-8">
        <title>CCH Admin Dashboard</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        {{ HTML::style('css/bootstrap.min.css'); }} 
        <!-- font Awesome -->
        {{ HTML::style('css/font-awesome.min.css'); }} 
        <!-- Ionicons -->
        {{ HTML::style('css/ionicons.min.css'); }} 
        <!-- Morris chart -->
        {{ HTML::style('css/morris/morris.css'); }} 
        <!-- jvectormap -->
        {{ HTML::style('css/jvectormap/jquery-jvectormap-1.2.2.css'); }} 
        <!-- fullCalendar -->
        {{ HTML::style('css/fullcalendar/fullcalendar.css'); }} 
        <!-- Daterange picker -->
        {{ HTML::style('css/datepicker/datepicker3.css'); }} 
        <!-- bootstrap wysihtml5 - text editor -->
        {{ HTML::style('css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css'); }} 
        <!-- Theme style -->
        {{ HTML::style('css/AdminLTE.css'); }} 

        {{ HTML::style('css/chosen/chosen.css'); }} 

        <!-- jQuery 2.0.2 -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <!-- jQuery UI 1.10.3 -->
        {{ HTML::script('js/jquery-ui-1.10.3.min.js'); }}
        <!-- Bootstrap -->
        {{ HTML::script('js/bootstrap.min.js'); }}
        <!-- Date picker -->
        {{ HTML::script("js/plugins/datepicker/bootstrap-datepicker.js"); }}
        {{ HTML::script("js/plugins/chosen/chosen.jquery.js"); }}
        <!-- Highcharts -->
        {{ HTML::script("js/plugins/highcharts/highcharts.js"); }}
        {{ HTML::script("js/plugins/highcharts/modules/drilldown.js"); }}
        {{ HTML::script("js/plugins/highcharts/highcharts-more.js"); }}

        <script type="text/javascript">
            var base_url = "http://188.226.189.149/cch/yabr3";

            var EventTracker = function() {
                this.register = [];
                this.add = function(payload) {
                    this.register.push(payload);
                }
                this.getParams = function() {
                    var params = {};

                    for(k in this.register)
                    {
                        params[this.register[k].name] = $(this.register[k].val).val();
                    }

                    return params;
                }
            }
        </script>

        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

	@show
    </head>

    <body class="skin-blue">
        <header class="header">
            <a href="/cch/yabr3/" class="logo">CCH Admin</a>

            <nav class="navbar navbar-static-top" role="navigation">
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">

                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>
                                <span>{{{ Auth::user()->getName() }}}<i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header bg-light-blue">
                                    {{ HTML::image('img/avatar3.png','User Image',array('class'=>'img-circle')); }}
                                    <p>
                                        {{{ Auth::user()->getName() }}} ({{{Auth::user()->username}}}) 
                                        <small>{{{ ucfirst(Auth::user()->role) }}}, {{{ Auth::user()->group }}}</small>
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="{{ URL::to('users/'.Auth::user()->id.'/edit') }}" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="{{ URL::to('logout') }}" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
                             {{ HTML::image('img/avatar3.png','User Image',array('class'=>'img-circle')); }}
                        </div>
                        <div class="pull-left info">
                            <p>Hello, {{{ Auth::user()->first_name }}}</p>

                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div>

                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        <li class="treeview {{ (Request::is('/') or Request::is('stayingwell*') or Request::is('indicators*') or Request::is('reports*')) ? 'active' : '' }}">
                            <a href="{{ URL::to('/') }}"> 
                                <i class="fa fa-dashboard"></i><span>Dashboard</span> 
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li class=" {{ Request::is('indicators*') ? 'active' : '' }}">
                                    <a href="{{ URL::to('indicators')}}"><i class="fa fa-angle-double-right"></i>Indicators</a>
                                </li>
                                <li class=" {{ Request::is('stayingwell*') ? 'active' : '' }}">
                                    <a href="{{ URL::to('stayingwell')}}"><i class="fa fa-angle-double-right"></i>Staying Well</a>
                                </li>
                                <li class=" {{ Request::is('reports*') ? 'active' : '' }}">
                                    <a href="{{ URL::to('reports') }}"><i class="fa fa-angle-double-right"></i>Reports</a>
				                </li>
                            </ul>
                        </li>

		    @if (in_array(strtolower(Auth::user()->role),  array('admin','dhio','dhio assistant')) || (strpos(strtolower(Auth::user()->role),"supervisor")>=0))

                        <li class="treeview {{ Request::is('content*') ? 'active' : '' }}">
                            <a href="{{ URL::to('/content') }}">
                                <i class="fa fa-bullseye"></i>
                                <span>Manage Content</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>

                            <ul class="treeview-menu">
                                <li class="treeview {{ Request::is('content/poccms/*') ? 'active' : '' }}">
                                    <a href="{{ URL::to('content/poccms') }}">
                                        <i class="fa fa-angle-double-right"></i>
                                        <span>Point of Care CMS</span>
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </a>
                                    <ul class="treeview-menu">
                                        <li class="{{ Request::is('content/poccms/view*') ? 'active' : '' }}">
                                            <a href="{{ URL::to('content/poccms/view') }}"><i class="fa fa-edit fa-fw"></i>View Pages</a>
                                        </li>
                                        <li class="{{ Request::is('content/poccms/add*') ? 'active' : '' }}">
                                            <a href="{{ URL::to('content/poccms/add') }}"><i class="fa fa-edit fa-fw"></i>Add Pages</a>
                                        </li>
                                        <li class="{{ Request::is('content/poccms/section*') ? 'active' : '' }}">
                                            <a href="{{ URL::to('content/poccms/section') }}"><i class="fa fa-edit fa-fw"></i>Add Sections</a>
                                        </li>
                                        <li class="{{ Request::is('content/poccms/upload*') ? 'active' : '' }}">
                                            <a href="{{ URL::to('content/poccms/upload') }}"><i class="fa fa-upload"></i>Upload Content</a>
                                        </li>
                                    </ul>
                               </li> 
                            </ul>
                        </li>

                        <li class="treeview {{ Request::is('targets/*') ? 'active' : '' }}">
                            <a href="#">
                                <i class="fa fa-bullseye"></i>
                                <span>Manage Targets</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li class="{{ Request::is('targets/actuals*') ? 'active' : '' }}">
                                    <a href="{{ URL::to('targets/actuals') }}"><i class="fa fa-angle-double-right"></i> Set Actuals</a>
                                </li>

                                <li class="treeview {{ Request::is('targets/population/*') ? 'active' : '' }}">
                                    <a href="#">
                                        <i class="fa fa-angle-double-right"></i>
                                        <span>Manage Populations</span>
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </a>
                                    <ul class="treeview-menu">
                                        <li class="{{ Request::is('targets/population/districts*') ? 'active' : '' }}">
                                            <a href="{{ URL::to('targets/population/districts') }}"><i class="fa fa-angle-right"></i>District Populations</a>
                                        </li>
                                        <li class="{{ Request::is('targets/population/subdistricts*') ? 'active' : '' }}">
                                            <a href="{{ URL::to('targets/population/subdistricts') }}"><i class="fa fa-angle-right"></i>Sub District Populations</a>
                                        </li>
                                        <li class="{{ Request::is('targets/population/zones*') ? 'active' : '' }}">
                                            <a href="{{ URL::to('targets/population/zones') }}"><i class="fa fa-angle-right"></i>Zone Populations</a>
                                        </li>
                                    </ul>
                               </li> 
                            </ul>
                        </li>
			@endif

		    @if (in_array(strtolower(Auth::user()->role),  array('admin','dhio','dhio assistant','district admin')) || (strpos(strtolower(Auth::user()->role),"supervisor")>=0))
                        <li class="treeview {{ (Request::is('devices*') or Request::is('facilities*')  or Request::is('users*') or Request::is('tracker*')) ? 'active' : '' }}">
                            <a href="#">
                                <i class="fa fa-cogs"></i>
                                <span>System Setup</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li class="{{ Request::is('devices/*') ? 'active' : '' }}"><a href="{{ URL::to('devices') }}"><i class="fa fa-mobile-phone"></i>Devices</a></li>
                                <li class="{{ Request::is('facilities/*') ? 'active' : '' }}"><a href="{{ URL::to('facilities') }}"><i class="fa fa-hospital-o"></i>Facilities</a></li>
                                <li class="{{ Request::is('facilitytypes/*') ? 'active' : ''}}"><a href="{{ URL::to('facilitytypes') }}"><i class="fa fa-hospital-o"></i>Facility Types</a></li>
                                <li class="{{ Request::is('districts/*') ? 'active' : '' }}"><a href="{{ URL::to('districts') }}"><i class="fa fa-mobile-phone"></i>District</a></li>
                                <li class="{{ Request::is('subdistricts/*') ? 'active' : '' }}"><a href="{{ URL::to('subdistricts') }}"><i class="fa fa-mobile-phone"></i>Sub District</a></li>
                                <li class="{{ Request::is('zones/*') ? 'active' : '' }}"><a href="{{ URL::to('zones') }}"><i class="fa fa-mobile-phone"></i>Zone</a></li>

                                <li class="{{ Request::is('districtadmin/*') ? 'active' : '' }}"><a href="{{ URL::to('districtadmin') }}"><i class="fa fa-users"></i>District Admins</a></li>
		                        @if (strtolower(Auth::user()->role) == 'admin' )
                                <li class="{{ Request::is('users/*') ? 'active' : '' }}"><a href="{{ URL::to('users') }}"><i class="fa fa-users"></i>Users</a></li>
                                @else
                                <li class="{{ Request::is('distusers/*') ? 'active' : '' }}"><a href="{{ URL::to('distusers') }}"><i class="fa fa-users"></i>Users</a></li>
                                @endif
                                <li class="{{ Request::is('tracker*') ? 'active' : '' }}"><a href="{{ URL::to('tracker') }}"><i class="fa fa-file"></i>Logs</a></li>
                            </ul>
                        </li>
			@endif
                        
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
		@yield('content-header')

                <!-- Main content -->
		@yield('content')
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->

        <!-- Morris.js charts -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
        {{ HTML::script('js/plugins/morris/morris.min.js'); }}
        <!-- Sparkline -->
        {{ HTML::script('js/plugins/sparkline/jquery.sparkline.min.js'); }}
        <!-- jvectormap -->
        {{ HTML::script('js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js'); }}
        {{ HTML::script('js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js'); }}
        <!-- fullCalendar -->
        {{ HTML::script('js/plugins/fullcalendar/fullcalendar.min.js'); }}
        <!-- jQuery Knob Chart -->
        {{ HTML::script('js/plugins/jqueryKnob/jquery.knob.js'); }}
        <!-- Bootstrap WYSIHTML5 -->
        {{ HTML::script('js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'); }}
        <!-- iCheck -->
        {{ HTML::script('js/plugins/iCheck/icheck.min.js'); }}
        <!-- data tables -->
        {{ HTML::script('js/plugins/datatables/jquery.dataTables.js'); }}
        {{ HTML::script('js/plugins/datatables/dataTables.bootstrap.js'); }}
        <!-- AdminLTE App -->
        {{ HTML::script('js/AdminLTE/app.js'); }}

	@yield('script')
    </body>
</html>


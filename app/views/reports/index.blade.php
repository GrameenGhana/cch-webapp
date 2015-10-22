@extends('layouts.master')

@section('head')
   @parent
   {{ HTML::style('css/datatables/dataTables.bootstrap.css'); }} 
@stop

@section('content-header')
	    
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1> <i class="fa fa-hospital-o"></i> Reports <small>Control panel</small> </h1>
                    <ol class="breadcrumb">
                        <li><a href="{{ URL::to('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Districts</li>
                    </ol>
                </section>
@stop

@section('content')

                <section class="content invoice">
<!-- title row -->
                    <div class="row">
                        <div class="col-xs-12">
                                                    
                        </div><!-- /.col -->
                    </div>
					@if (Session::has('message'))
						<div class="alert alert-info">{{ Session::get('message') }}</div>
					@endif

                    <div class="box">
                                <div class="box-header">
                                </div><!-- /.box-header -->
                                <div class="box-body table-responsive">
                                    <table id="factable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Report Name</th>
                                  
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
					

                                             <tr>
                                                  <td>User</td>

                                                  <td>
                                                        <a title="View Reports" class="btn btn-sm btn-info" href="/reports/cch.export.php?action=cchuserdevice&d={{$d}}"><i class="fa fa-bar-chart-o">View Report</i></a>
                                                  </td>
                                             </tr>

                                             <tr>
                                                  <td>Device Users</td>

                                                  <td>
                                                        <a title="View Reports" class="btn btn-sm btn-info" href="/reports/cch.export.php?action=cchdeviceuser&d={{$d}}"><i class="fa fa-bar-chart-o">View Report</i></a>
                                                  </td>
                                             </tr>



					
                                             <tr>
                                                  <td>Facilities</td>

                                                  <td>
                                                        <a title="View Reports" class="btn btn-sm btn-info" href="/reports/cch.export.php?action=cchfacilities&d={{$d}}"><i class="fa fa-bar-chart-o">View$
                                                  </td>
                                             </tr>

    <tr>
                                                  <td>Survey Data</td>

                                                  <td>
                                                        <a title="View Reports" class="btn btn-sm btn-info" href="/reports/cch.export.php?action=cchsurvey&d={{$d}}"><i class="fa fa-bar-chart-o">View Report</i></a>
                                                  </td>
                                             </tr>

    <tr>
                                                  <td>User version</td>

                                                  <td>
                                                        <a title="View Reports" class="btn btn-sm btn-info" href="/reports/cch.export.php?action=cchuserversion&d={{$d}}"><i class="fa fa-bar-chart-o">View Report</i></a>
                                                  </td>
                                             </tr>

  <tr>
                                                  <td>User Last Sync Data</td>

                                                  <td>
                                                        <a title="View Reports" class="btn btn-sm btn-info" href="/reports/cch.export.php?action=cchuserlastsync&d={{$d}}"><i class="fa fa-bar-chart-o">View Report</i></a>
                                                  </td>
                                             </tr>



					</tbody>
				     </table>
				</div>
			</div>
   		</section>	
@stop
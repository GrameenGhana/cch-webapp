@extends('layouts.master')

@section('head')
   @parent
   {{ HTML::style('css/datatables/dataTables.bootstrap.css'); }} 
@stop

@section('content-header')
	    
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1> <i class="fa fa-files-o"></i> Logs <small>Control panel</small> </h1>
                    <ol class="breadcrumb">
                        <li><a href="{{ URL::to('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">App Usage Logs</li>
                    </ol>
                </section>
@stop

@section('content')

                <section class="content invoice">
<!-- title row -->
						@if (Session::has('message'))
						<div class="alert alert-info">{{ Session::get('message') }}</div>
					@endif

                    <div class="box">
                                <div class="box-header">
                                </div><!-- /.box-header -->
                                <div class="box-body table-responsive">
                                    <table id="logstable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Staff</th>
                                                <th>Module</th>
                                                <th>Section</th>
                                                <th>Date</th>
                                                <th>Duration (s)</th>
                                                <th>Date Submitted</th>
                                            </tr>
                                        </thead>
                                        <tbody>
					@if (count($logs) > 0)
					    @foreach($logs as $k => $value) 
					     <tr>
						  <td> {{ $value->user->username }} </td>
						  <td> {{ $value->module }} </td>
						  <td> {{ $value->data}} </td>
						  <td> {{ date('M d, Y h:m:s a',($value->start_time / 1000)) }} </td>
						  <td> {{ $value->timetaken}} </td>
						  <td> {{ date('M d, Y',strtotime($value->created_at)) }} </td>
					     </tr>
					     @endforeach
					@else 
						<tr><td colspan="6">No logs found</td></tr>
					@endif
					</tbody>
				     </table>
				</div>
			</div>
   		</section>	
@stop


@section('script')
	<script type="text/javascript">
            $(function() {
                $('#logstable').dataTable({
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bSort": true,
                    "bInfo": true,
                    "bAutoWidth": false
                });
            });
        </script>
@stop

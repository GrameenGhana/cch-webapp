@extends('layouts.master')

@section('head')
   @parent
   {{ HTML::style('css/datatables/dataTables.bootstrap.css'); }} 
@stop


@section('content-header')

                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1> <i class="fa fa-users"></i> Users <small>Control panel</small> </h1>
                    <ol class="breadcrumb">
                        <li><a href="{{ URL::to('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Logs</li>
                    </ol>
                </section>
@stop

@section('content')

                <section class="content invoice">
                   @if (Session::has('message'))
                           <div class="alert alert-info">{{ Session::get('message') }}</div>
                    @endif

                <section class="content">
                    <div class="box">
                                <div class="box-header">
                                </div><!-- /.box-header -->
                                <div class="box-body table-responsive">
                                    <table id="logstable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Resource</th>
						                        <th>User</th>
						                        <th>Action</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
					                    @foreach($logs as $r => $val) 
					                        @foreach($val as $v) 
                                               {{ $v }}
					                        @endforeach
					                    @endforeach
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
                    "bLengthChange": true,
                    "bFilter": true,
                    "bSort": true,
                    "bInfo": true,
                    "bAutoWidth": false,
                    "iDisplayLength": 100
                });
            });
        </script>
@stop


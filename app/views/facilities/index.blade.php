@extends('layouts.master')

@section('head')
   @parent
   {{ HTML::style('css/datatables/dataTables.bootstrap.css'); }} 
@stop

@section('content-header')
	    
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1> <i class="fa fa-hospital-o"></i> Facilities <small>Control panel</small> </h1>
                    <ol class="breadcrumb">
                        <li><a href="{{ URL::to('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Facilities</li>
                    </ol>
                </section>
@stop

@section('content')

                <section class="content invoice">
<!-- title row -->
@if (Auth::user()->role == 'Admin')
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header">
				    <a class="btn btn-small btn-success" href="{{ URL::to('facilities/create') }}"><i class="fa fa-plus-circle"></i> Add a Facility</a>
                            </h2>                            
                        </div>
                        <!-- /.col -->
                    </div>
@endif
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
                                                <th>Name</th> 
                                                <th>Facility Type</th>
                                                <th>Motech ID</th>
                                                <th>Sub District</th>
                                                <th>District</th>
                                                <th>Region</th>
                                                <th>Last Updated</th>
                                                <th>Modified By</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
					@foreach($facilities as $k => $value) 
					     <tr>
						  <td> {{ $value->name }} </td>
						  <td> {{ $value->facility_type }} </td>
						  <td> {{ $value->motech_facility_id or "Non-Motech" }} </td>
						  <td> {{ @$value->facSubDistrict->name}} </td>
						  <td> {{ @$value->facDistrict->name}} </td>
						  <td> {{ @$value->facDistrict->region}} </td>
						  <td> {{ date('M d, Y',strtotime($value->updated_at)) }} </td>
						  <td> {{ $value->modifier->username }} </td>
						  <td>
							<a title="Edit" class="btn btn-sm btn-info" href="{{ URL::to('facilities/' . $value->id . '/edit') }}"><i class="fa fa-pencil"></i></a>
                            <a title="View calendar" class="btn btn-sm btn-info" href="{{ URL::to('facilities/calendar/' . $value->id ) }}"><i class="fa fa-calendar"></i></a>
                            <a title="View people in facility" class="btn btn-sm btn-info" href="{{ URL::to('facilities/people/' . $value->id ) }}"><i class="fa fa-users"></i></a>
							<!--
							<a class="btn btn-sm btn-danger" href="{{ URL::to('facilities/' . $value->id . '/delete') }}">Delete</a>
							-->
						  </td>
					     </tr>
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
                $('#factable').dataTable({
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



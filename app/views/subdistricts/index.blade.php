@extends('layouts.master')

@section('head')
   @parent
   {{ HTML::style('css/datatables/dataTables.bootstrap.css'); }} 
@stop

@section('content-header')
	    
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1> <i class="fa fa-hospital-o"></i> Sub Districts <small>Control panel</small> </h1>
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
 @if (strtolower(Auth::user()->role) == 'admin'  || strtolower(Auth::user()->role)=='district admin')

                            <h2 class="page-header">
				    <a class="btn btn-small btn-success" href="{{ URL::to('subdistricts/create') }}"><i class="fa fa-plus-circle"></i> Add a Sub District</a>
                            </h2>                            
@endif	
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
                                                <th>Name</th>
                                                <th>District</th>
                                               
                                  
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
					@foreach($subdistricts as $k => $value) 
					     <tr>
						  <td> {{ $value->name }} </td>
						  <td> {{ $value->district->name}} </td>
						
						  <td>
							<a title="Edit" class="btn btn-sm btn-info" href="{{ URL::to('subdistricts/' . $value->id . '/edit') }}"><i class="fa fa-pencil"></i></a>
                            <a title="View people in district" class="btn btn-sm btn-info" href="{{ URL::to('subdistricts/people/' . $value->id ) }}"><i class="fa fa-users"></i></a>
							<!--
							<a class="btn btn-sm btn-danger" href="{{ URL::to('districts/' . $value->id . '/delete') }}">Delete</a>
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


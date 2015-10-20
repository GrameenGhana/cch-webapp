@extends('layouts.master')

@section('head')
   @parent
   {{ HTML::style('css/datatables/dataTables.bootstrap.css'); }} 
@stop

@section('content-header')
	    
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1> <i class="fa fa-mobile-phone"></i> Devices <small>Control panel</small> </h1>
                    <ol class="breadcrumb">
                        <li><a href="{{ URL::to('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Devices</li>
                    </ol>
                </section>
@stop

@section('content')

                <section class="content invoice">
<!-- title row -->
      @if (strtolower(Auth::user()->role) != 'district admin')
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header">
				    <a class="btn btn-small btn-success" href="{{ URL::to('devices/create') }}"><i class="fa fa-plus-circle"></i> Add a Device</a>
                            </h2>                            
                        </div><!-- /.col -->
                    </div>
     @endif
					@if (Session::has('message'))
						<div class="alert alert-info">{{ Session::get('message') }}</div>
					@endif

                    <div class="box">
                                <div class="box-header">
                                </div><!-- /.box-header -->
                                <div class="box-body table-responsive">
                                    <table id="devicetable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Tag</th>
                                                <th>IMEI</th>
                                                <th>Color</th>
                                                <th>Status</th>
                                                <th>Owner</th>
                                                <th>Comment</th>

                                                <th>Last Updated</th>
                                                <th>Modified By</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
					@foreach($devices as $k => $value) 
					     <tr>
						  <td> {{ $value->type }} </td>
						  <td> {{ $value->tag or "No Tag" }} </td>
						  <td> {{ $value->imei }} </td>
						  <td> {{ $value->color }} </td>
						  <td> {{ ucfirst($value->status) }} </td>
						  
						 <td> {{ $value->getUserName() }} </td>
                                                  <td> {{ $value->comment }} </td>
						 
 <td> {{ date('M d, Y',strtotime($value->updated_at)) }} </td>
						  <td> {{ $value->modifier->username }} </td>
						  <td>
							<a title="Edit" class="btn btn-sm btn-info" href="{{ URL::to('devices/' . $value->id . '/edit') }}"><i class="fa fa-pencil"></i></a>
							<!--
							<a class="btn btn-sm btn-danger" href="{{ URL::to('devices/' . $value->id . '/delete') }}">Delete</a>
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
                $('#devicetable').dataTable({
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bSort": true,
                    "bInfo": true,
                    "bAutoWidth": false,
			 "sDom": 'T<"clear">lfrtip',
        "oTableTools": {
            "aButtons": [
                "copy",
                "print",
                {
                    "sExtends":    "collection",
                    "sButtonText": "Save",
                    "aButtons":    [ "csv", "xls", "pdf" ]
                }
            ]
        }
                });
            });
        </script>
@stop


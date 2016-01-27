@extends('layouts.master')

@section('head')
   @parent
   {{ HTML::style('css/datatables/dataTables.bootstrap.css'); }}
   <style type="text/css">
      #factable_filter { margin-top:-25px;}

      input:focus, textarea:focus {
        background-color: #FFFFCC;
      }
   </style>

@stop


@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <i class="fa fa-hospital-o"></i> POC CMS</h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">POC CMS</li>
        </ol>
    </section>
@stop


@section('content')
<section class="content">
<div class="col-sm-12">

<div class="row">
	<div class="col-sm-12">
		@section ('cotable_panel_title','Uploads')
		@section ('cotable_panel_body')
		  @if ($errors->any())
            <ul>
            {{ implode('', $errors->all('<div class="alert alert-danger fade in"> <a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> :message</div>')) }}
            </ul>
        @endif
        @if (Session::has('message'))
            <div class="alert alert-success fade in"> <a href="#" class="close" data-dismiss="alert">&times;</a><strong>Success!</strong> {{ Session::get('message') }}</div>
        @endif
        <div class="dataTable_wrapper"> 
		<table class="table table-bordered" id="dataTables-example">
			<thead>
				<tr>
					<th>Section</th>
					<th>Sub Section</th>
					<th>Shortname</th>
					<th>Upload Status</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
			  @foreach($sections as $section)
				<tr>
					<td>{{$section->name_of_section}}</td>
					<td>{{$section->sub_section}}</td>
					<td>{{$section->shortname}}</td>
					@if($section->upload_status=='') 
						<td>
                            <a href="uploadFiles?id={{ $section->id }}"><button type="button" class="btn btn-warning btn-circle" id="{{$section->id}}"><i class="fa fa-upload"></i></button>
                        </td>
  					@else
  						<td>{{$section->upload_status}}</td>
					@endif
					<td>
					<a href="editsection?id={{$section->id}}"><button type="button" class="btn btn-warning btn-circle edit" id="{{$section->id}}"><i class="fa fa-pencil"></i></button></a>
					<a href="deletesection?id={{$section->id}}"><button type="button" class="btn btn-danger btn-circle delete" id="{{$section->id}}"><i class="fa fa-times" ></i></button></a>
					</td>
				</tr>
				 @endforeach
				
			</tbody>
		</table>	
		</div>
	
		@endsection
		@include('content.poc.widgets.panel', array('header'=>true, 'as'=>'cotable'))
	</div>
</div>
</div>
</section>
@stop

@section('script')
<script type="text/javascript" charset="utf8">
    $('#dataTables-example').DataTable({
         responsive: true,
        paging: true
    });
    $(document).ready(function(){ });
</script>
@stop

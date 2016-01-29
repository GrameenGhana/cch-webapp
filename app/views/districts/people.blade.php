@extends('layouts.master')

@section('head')
   @parent
   {{ HTML::style('css/datatables/dataTables.bootstrap.css'); }} 
@stop

@section('content-header')

                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1> <i class="fa fa-hospital-o"></i> Districts <small>Control panel</small> </h1>
                    <ol class="breadcrumb">
                        <li><a href="{{ URL::to('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Districts</li>
                    </ol>
                </section>
@stop

@section('content')

<section class="content invoice">

	<div class="row">
        	<div class="col-xs-12">
                	<h2 class="page-header">{{ $district->name }}'s People</h2>
                </div>
        </div>
        <div class="box-body table-responsive">
                         <table id="nursestable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Title</th>
                                        <th>Phone number</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                               @foreach($users as $k => $value)
                                     <tr>
                                          <td> {{ $value->getName() }} </td>
                                          <td> {{ $value->role }} </td>
					                      <td> {{ $value->title or 'No title' }} </td>
                                          <td> {{ $value->phone_number or 'No number' }} </td>
  <td>
                                                        <a title="Edit" class="btn btn-sm btn-info" href="{{ URL::to('users/' . $value->id . '/edit') }}"><i class="fa fa-pencil"></i></a>
                                                        @if ($value->role == 'Nurse')
                                                        <a title="View calendar" class="btn btn-sm btn-info" href="{{ URL::to('users/calendar/' . $value->id ) }}"><i class="fa fa-calendar"></i></a>
                                                        <a title="View courses" class="btn btn-sm btn-info" href="{{ URL::to('users/courses/' . $value->id ) }}"><i class="fa fa-book"></i></a>
                                                        @endif
                                                  </td>
                                     </tr>
                                @endforeach
                                </tbody>
                        </table>
               </div>
</section>
@stop

@section('script')
        <script type="text/javascript">
		$('#nursestable').dataTable({
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bSort": true,
                    "bInfo": true,
                    "bAutoWidth": false
                });
           
        </script>
@stop


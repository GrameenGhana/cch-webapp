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
                        <li><a href="{{ URL::to('/users/') }}"><i class="fa fa-dashboard"></i> Users</a></li>
                        <li class="active">Courses</li>
                    </ol>
                </section>
@stop

@section('content')

<section class="content invoice">
   <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">{{{ $user->getName() }}} completed courses</h2>
        </div>
    </div>

   <div class="box-body table-responsive">
        <table id="userstable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Course</th>
                                   <!--
                                                <th>Lesson</th>
                                                <th>Topic</th>
                                                <th>Time spent</th>
                                                -->
                                            </tr>
                                        </thead>
                                        <tbody>
					@foreach($courses as $course => $info) 
                           <tr>
						        <td> {{{ $course }}} </td>
 					    @foreach($info as $activity => $in) 
					        @foreach($in as $value) 
                                @if($value['done']=='Completed')
                                   <!--
						        <td> {{{ $activity }}} </td>
						        <td> {{{ $value['section'] }}} </td>
						        <td> {{{ $value['time'] }}} </td>
                                -->
                            </tr>
                                @endif
                           @endforeach
                        @endforeach
					@endforeach
					</tbody>
	     </table>
	</div>
</section>	


<section class="content invoice">
   <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">{{{ $user->getName() }}} ongoing courses</h2>
        </div>
    </div>

   <div class="box-body table-responsive">
        <table id="userstable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Course</th>
                                   <!--
                                                <th>Lesson</th>
                                                <th>Topic</th>
                                                <th>Time spent</th>
                                                -->
                                            </tr>
                                        </thead>
                                        <tbody>
					@foreach($courses as $course => $info) 
                           <tr>
						        <td> {{{ $course }}} </td>
 					    @foreach($info as $activity => $in) 
					        @foreach($in as $value) 
                                @if($value['done']!='Completed')
                                  <!--
						        <td> {{{ $activity }}} </td>
 						        <td> {{{ $value['section'] }}} </td>
						        <td> {{{ $value['time'] }}} </td>
                                    -->
                            </tr>
                              @endif
                           @endforeach
                        @endforeach
					@endforeach
					</tbody>
	     </table>
	</div>
</section>
@stop


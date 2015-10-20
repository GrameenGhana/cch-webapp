@extends('layouts.master')

@section('content-header')
	    
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1> Dashboard <small>Control panel</small> </h1>
                    <ol class="breadcrumb">
                        <li><a href="{{ URL::to('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Dashboard</li>
                    </ol>
                </section>
@stop

@section('content')
                <!-- Main content -->
                <section class="content">

		@if (strtolower(Auth::user()->role) == 'supervisor')
			@include('supervisor.index', array('nurses'=>$nurses));

		@elseif (strtolower(Auth::user()->role) == 'district supervisor')
			@include('supervisor.district', array('nurses'=>$nurses));

		@else
			@include('home');
		@endif

               </section>
	       <!-- /.content -->
@stop

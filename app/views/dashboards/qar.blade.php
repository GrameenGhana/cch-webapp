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

                    @include('widgets.style')                  

                    <div class="row">
                        <div class="col-lg-12 col-xs-12">
                            <h1> Quality Assurance Reports </h1><hr/>
                        </div>
                    </div>
    
                    @include('widgets.cch-qar-lastsync',array('dashboard'=>$dashboard))                  

                    @include('widgets.cch-qar-viuactive',array('dashboard'=>$dashboard))                  
               </section>
	       <!-- /.content -->
@stop

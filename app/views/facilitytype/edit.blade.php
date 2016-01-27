@extends('layouts.master')

@section('head')
@parent
{{ HTML::style('css/datatables/dataTables.bootstrap.css'); }} 
@stop

@section('content-header')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1> <i class="fa fa-hospital-o"></i> Facility Types <small>Control panel</small> </h1>
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ URL::to('facilitytypes') }}"><i class="fa fa-hospital-o"></i> Facility Types</a></li>
        <li class="active">Edit</li>
    </ol>
</section>
@stop

@section('content')

<section class="content invoice">
    <!-- title row -->
    <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">Create a Facility Type</h2>                            
        </div><!-- /.col -->
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                {{ Form::open(array('url'=> 'facilitytype/'.$facilitytype->id,'method'=>'put')) }}

                <div class="box-body">
                    @if(Session::has('flash_error'))
                    <div class="callout callout-danger">
                        <h4>Error!</h4> <br/>
                        {{ HTML::ul($errors->all()) }}
                    </div>
                    @endif

                    <div class="form-group">
                        {{ Form::label('code','Code') }}
                        {{ Form::text('code',$facilitytype->id,array('class'=>'form-control','placeholder'=>'Enter Code','readonly'=>"true")) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('name','Name') }}
                        {{ Form::text('name',$facilitytype->name,array('class'=>'form-control','placeholder'=>'Enter Name')) }}
                    </div>
                </div><!-- /.box-body -->

                <div class="box-footer">
                    {{ Form::submit('Edit Facility Type',array('class'=>'btn btn-primary')) }}
                </div>

                {{ Form::close() }}
            </div><!-- /.box -->
        </div>
    </div>
</section>	
@stop


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
                        <li><a href="{{ URL::to('districts') }}"><i class="fa fa-hospital-o"></i> Districts</a></li>
                        <li class="active">Edit</li>
                    </ol>
                </section>
@stop

@section('content')

                <section class="content invoice">
<!-- title row -->
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header">Create a Distirct</h2>                            
                        </div><!-- /.col -->
                    </div>

		    <div class="row">
		        <div class="col-md-6">
		    		<div class="box box-primary">
			       {{ Form::open(array('url'=> 'districts/'.$district->id,'method'=>'put')) }}

                               	    <div class="box-body">
					@if(Session::has('flash_error'))
					<div class="callout callout-danger">
                                        	<h4>Error!</h4> <br/>
						{{ HTML::ul($errors->all()) }}
                                    	</div>
					@endif
	
                                        <div class="form-group">
					    {{ Form::label('name','Name') }}
					    {{ Form::text('name',$district->name,array('class'=>'form-control','placeholder'=>'Enter district name')) }}
                                        </div>
                                        <div class="form-group">
					    {{ Form::label('region','Region') }}
                                            {{ Form::select('region',$region,$district->region,array('class'=>'form-control','placeholder'=>'Enter Region to which this district belongs'))}}
					 
                                        </div>
                                    </div><!-- /.box-body -->

                                    <div class="box-footer">
					{{ Form::submit('Edit District',array('class'=>'btn btn-primary')) }}
                                    </div>

				    {{ Form::close() }}
                      		    </div><!-- /.box -->
				</div>
			</div>
   		</section>	
@stop


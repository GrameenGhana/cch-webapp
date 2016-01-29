@extends('layouts.master')

@section('head')
   @parent
   {{ HTML::style('css/datatables/dataTables.bootstrap.css'); }} 
@stop

@section('content-header')
	    
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1> <i class="fa fa-hospital-o"></i> Devices <small>Control panel</small> </h1>
                    <ol class="breadcrumb">
                        <li><a href="{{ URL::to('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="{{ URL::to('devices') }}"><i class="fa fa-hospital-o"></i> Devices</a></li>
                        <li class="active">Edit</li>
                    </ol>
                </section>
@stop

@section('content')

                <section class="content invoice">
<!-- title row -->
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header">Edit {{{ $device->tag }}} Device</h2>                            
                        </div><!-- /.col -->
                    </div>

		    <div class="row">
		        <div class="col-md-6">
		    		<div class="box box-primary">

                               {{ Form::open(array('url'=> 'devices/'.$device->id,'method'=>'post')) }}
					<input type="hidden" name="_method" value="PATCH" />
                               	    <div class="box-body">
					@if(Session::has('flash_error'))
					<div class="callout callout-danger">
                                        	<h4>Error!</h4> <br/>
						{{ HTML::ul($errors->all()) }}
                                    	</div>
					@endif
                                        <div class="form-group">
                       {{ Form::label('type','Type') }}
                        {{ Form::select('type', $types, $device->type,array('class'=>'form-control','placeholder'=>'Enter device type')) }}
                                        </div>
                                        <div class="form-group">
                        {{ Form::label('tag','Tag') }}
                        {{ Form::text('tag',$device->tag,array('class'=>'form-control','placeholder'=>'Enter device tag')) }}
                                        </div>
                                        <div class="form-group">
                        {{ Form::label('imei','IMEI') }}
                        {{ Form::text('imei',$device->imei,array('class'=>'form-control','placeholder'=>'Enter device imei')) }}
                                        </div>
                                        <div class="form-group">
                        {{ Form::label('color','Color') }}
                        {{ Form::select('color', array('Black'=>'Black','White'=>'White'), $device->color,array('class'=>'form-control','placeholder'=>'Enter device color')) }}
                                        </div>
                                        <div class="form-group">
                        {{ Form::label('status','Status') }}
                        {{ Form::select('status', $status, $device->status,array('class'=>'form-control','placeholder'=>'Enter device status')) }}
                                        </div>	
                                        <div class="form-group">
                        {{ Form::label('user','Assigned To') }}                        
                        {{ Form::select('user', $users, $device->user_id, array('class'=>'form-control','placeholder'=>'Enter device owner')) }}
                                        </div>

                                    </div><!-- /.box-body -->

                                    <div class="box-footer">
					{{ Form::submit('Update Device',array('class'=>'btn btn-primary')) }}
                                    </div>

				    {{ Form::close() }}
                      		    </div><!-- /.box -->
				</div>
			</div>
   		</section>	
@stop

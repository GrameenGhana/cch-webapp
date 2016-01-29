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
                        <li><a href="{{ URL::to('devices') }}"><i class="fa fa-mobile-phone"></i> Devices</a></li>
                        <li class="active">Create</li>
                    </ol>
                </section>
@stop

@section('content')

                <section class="content invoice">
<!-- title row -->
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header">Create a Device</h2>                            
                        </div><!-- /.col -->
                    </div>

		    <div class="row">
		        <div class="col-md-6">
		    		<div class="box box-primary">
			       {{ Form::open(array('url'=> 'devices','method'=>'post')) }}

                               	    <div class="box-body">
					@if(Session::has('flash_error'))
					<div class="callout callout-danger">
                                        	<h4>Error!</h4> <br/>
						{{ HTML::ul($errors->all()) }}
                                    	</div>
					@endif
	
                                        <div class="form-group">
					    {{ Form::label('type','Type') }}
					    {{ Form::select('status', $types, Input::old('type'),array('class'=>'form-control','placeholder'=>'Enter device type')) }}
                                        </div>
                                        <div class="form-group">
					    {{ Form::label('tag','Tag') }}
					    {{ Form::text('tag',Input::old('tag'),array('class'=>'form-control','placeholder'=>'Enter device tag')) }}
                                        </div>
                                        <div class="form-group">
					    {{ Form::label('imei','IMEI') }}
					    {{ Form::text('imei',Input::old('imei'),array('class'=>'form-control','placeholder'=>'Enter device imei')) }}
                                        </div>
                                        <div class="form-group">
					    {{ Form::label('color','Color') }}
					    {{ Form::select('color', array('Black'=>'Black','White'=>'White'), Input::old('color'),array('class'=>'form-control','placeholder'=>'Enter device color')) }}
                                        </div>
                                        <div class="form-group">
					    {{ Form::label('status','Status') }}
					    {{ Form::select('status', $status, Input::old('status'),array('class'=>'form-control','placeholder'=>'Enter device status')) }}
                                        </div>
                                        <div class="form-group">
					    {{ Form::label('district','District') }}
					    {{ Form::select('district', $region, Input::old('district'),array('class'=>'form-control','placeholder'=>'Enter device owner')) }}
                                        </div>
                                        <div class="form-group">
					    {{ Form::label('user','Assigned To') }}
					    {{ Form::select('user', $users, Input::old('user'),array('class'=>'form-control','placeholder'=>'Enter device owner')) }}
                                        </div>
            <div class="form-group">
                                            {{ Form::label('comment','Comment') }}
                                            {{ Form::text('comment',Input::old('comment'),array('class'=>'form-control','placeholder'=>'Enter device Comment')) }}
                                        </div>

                                    </div><!-- /.box-body -->

                                    <div class="box-footer">
					{{ Form::submit('Create Device',array('class'=>'btn btn-primary')) }}
                                    </div>

				    {{ Form::close() }}
                      		    </div><!-- /.box -->
				</div>
			</div>
   		</section>	
@stop
@section('script')
    <script type="text/javascript">
    $(document).ready(function()
    {
      var address= "/cch/yabr3";

      // when any option from district list is selected
    $("select[name='district']").change(function(){ 
      // get the selected option value of region
      var optionValue = jQuery("select[name='district']").val();   
     //   alert(optionValue);
      $("#user").load(address +'/getUsersInDistricts', "district="+optionValue, function(response){
          if(response) {
            $("#user").html(response);
          } 
      });     
    });
    });

  </script>
@stop

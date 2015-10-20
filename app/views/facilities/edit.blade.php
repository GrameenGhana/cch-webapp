@extends('layouts.master')

@section('head')
   @parent
   {{ HTML::style('css/datatables/dataTables.bootstrap.css'); }} 
@stop

@section('content-header')
	    
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1> <i class="fa fa-hospital-o"></i> Facilities <small>Control panel</small> </h1>
                    <ol class="breadcrumb">
                        <li><a href="{{ URL::to('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="{{ URL::to('facilities') }}"><i class="fa fa-hospital-o"></i> Facilities</a></li>
                        <li class="active">Edit</li>
                    </ol>
                </section>
@stop

@section('content')

                <section class="content invoice">
<!-- title row -->
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header">Edit {{{ $facility->name }}} Facility</h2>                            
                        </div><!-- /.col -->
                    </div>

		    <div class="row">
		        <div class="col-md-6">
		    		<div class="box box-primary">

                               {{ Form::open(array('url'=> 'facilities/'.$facility->id,'method'=>'post')) }}
					<input type="hidden" name="_method" value="PATCH" />
                               	    <div class="box-body">
					@if(Session::has('flash_error'))
					<div class="callout callout-danger">
                                        	<h4>Error!</h4> <br/>
						{{ HTML::ul($errors->all()) }}
                                    	</div>
					@endif
	
                                        <div class="form-group">
					    {{ Form::label('name','Name') }}
					    {{ Form::text('name',$facility->name,array('class'=>'form-control','placeholder'=>'Enter facility name')) }}
                                        </div>
                                          <div class="form-group">
					    {{ Form::label('fac_type','Facility Type') }}
					    {{ Form::select('fac_type', $facilityTypes,  $facility->facility_type,array('class'=>'form-control','placeholder'=>'Choose Facility Type')) }}
                                        </div>
                                        <div class="form-group">
					    {{ Form::label('district','District') }}
					    {{ Form::select('district', $districts, $facility->district,array('class'=>'form-control')) }}
                                        </div>
  <div class="form-group">
                        {{ Form::label('sub_district','Sub District') }}
                                            {{ Form::select('sub_district', $subdistricts,$facility->sub_district ,array('class'=>'form-control','placeholder'=>'Enter district name')) }}

                                         </div>
                                        <div class="form-group">
					    {{ Form::label('motechid','MOTECH Facility Id') }}
					    {{ Form::text('motechid',($facility->motech_facility_id==0 ? "": $facility->motech_facility_id),array('class'=>'form-control','placeholder'=>'Enter MOTECH Facility ID')) }}
					    <p>This is only required if this is a MOTECH facility</p>
                                        </div>
                                    </div><!-- /.box-body -->

                                    <div class="box-footer">
					{{ Form::submit('Update Facility',array('class'=>'btn btn-primary')) }}
                                    </div>

				    {{ Form::close() }}
                      		    </div><!-- /.box -->
				</div>
			</div>
   		</section>	
@stop


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
                        <li class="active">Create</li>
                    </ol>
                </section>
@stop

@section('content')

                <section class="content invoice">
<!-- title row -->
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header">Create a Facility</h2>                            
                        </div><!-- /.col -->
                    </div>

		    <div class="row">
		        <div class="col-md-6">
		    		<div class="box box-primary">
			       {{ Form::open(array('url'=> 'facilities','method'=>'post')) }}

                               	    <div class="box-body">
					@if(Session::has('flash_error'))
					<div class="callout callout-danger">
                                        	<h4>Error!</h4> <br/>
						{{ HTML::ul($errors->all()) }}
                                    	</div>
					@endif
	
                                        <div class="form-group">
					    {{ Form::label('name','Name') }}
					    {{ Form::text('name',Input::old('name'),array('class'=>'form-control','placeholder'=>'Enter facility name')) }}
                                        </div>
					  <div class="form-group">
					    {{ Form::label('fac_type','Facility Type') }}
					    {{ Form::select('fac_type', $facilityTypes, Input::old('fac_type'),array('class'=>'form-control','placeholder'=>'Choose Facility Type')) }}
                                        </div>

                                        <div class="form-group">
					    {{ Form::label('district','District') }}
					    {{ Form::select('district', $districts, Input::old('district'),array('class'=>'form-control','placeholder'=>'Enter district name')) }}
                                        </div>

                                        <div class="form-group">
					  {{ Form::label('sub_district','Sub District') }}
                        <select id="sub_district" name="sub_district" class="form-control">
                            <option>Select a district first</option>
                        </select>
                                        </div>

                                        <div class="form-group">
					    {{ Form::label('motechid','MOTECH Facility Id') }}
					    {{ Form::text('motechid',Input::old('motechid'),array('class'=>'form-control','placeholder'=>'Enter MOTECH Facility ID')) }}
					    <p>This is only required if this is a MOTECH facility</p>
                                        </div>
                                    </div><!-- /.box-body -->

                                    <div class="box-footer">
					{{ Form::submit('Create Facility',array('class'=>'btn btn-primary')) }}
                                    </div>

				    {{ Form::close() }}
                      		    </div><!-- /.box -->
				</div>
			</div>
   		</section>	
@stop

@section('script')
        <script type="text/javascript">
             function getsubs(did)
             {
					        $.get("{{ url('api/v1/dropdown/subdistricts')}}", { id: did }, function(data) {
					            $('#sub_district').empty(); 
					            $.each(data, function(key, element) {
					                $('#sub_district').append("<option value='" + key +"'>" + element + "</option>");
					            });
					        });
             }
					$(document).ready(function($){
					    $('#district').change(function(){ getsubs($(this).val()); });
                        getsubs($('#district').val());
					});
        </script>
@stop

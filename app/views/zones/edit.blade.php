@extends('layouts.master')

@section('head')
   @parent
   {{ HTML::style('css/datatables/dataTables.bootstrap.css'); }} 
@stop

@section('content-header')
	    
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1> <i class="fa fa-hospital-o"></i> Zones <small>Control panel</small> </h1>
                    <ol class="breadcrumb">
                        <li><a href="{{ URL::to('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="{{ URL::to('zones') }}"><i class="fa fa-hospital-o"></i> Zones</a></li>
                        <li class="active">Edit</li>
                    </ol>
                </section>
@stop

@section('content')

                <section class="content invoice">
<!-- title row -->
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header">Update Zone</h2>                            
                        </div><!-- /.col -->
                    </div>

		    <div class="row">
		        <div class="col-md-6">
		    		<div class="box box-primary">
			       {{ Form::open(array('url'=> 'zones/'.$zone->id,'method'=>'put')) }}

                               	    <div class="box-body">
					@if(Session::has('flash_error'))
					<div class="callout callout-danger">
                                        	<h4>Error!</h4> <br/>
						{{ HTML::ul($errors->all()) }}
                                    	</div>
					@endif

                    
                                        <div class="form-group">
                        {{ Form::label('name','Name') }}
                        {{ Form::text('name',$zone->name,array('class'=>'form-control','placeholder'=>'Enter zone name')) }}
                                        </div>
                                      

                    <div class="form-group">
                                {{ Form::label('region','Region') }}
                                {{ Form::select('region',$regions,$zone->region,array('class'=>'form-control','placeholder'=>'Enter Region to which this district belongs'))}}
           
                            </div>


                           <div class="form-group">
                               {{ Form::label('district','District') }}
                               {{ Form::select('district',$districts,$zone->district_id,array('class'=>'form-control','placeholder'=>'Select district'))}}

                           </div>

                             <div class="form-group">
                                {{ Form::label('subdistrict','Sub District') }}
                                 {{ Form::select('subdistrict',$subdistricts,$zone->subdistrict_id,array('class'=>'form-control','placeholder'=>'Enter Region to which this district belongs'))}}
                     
                             </div>

                              <div class="form-group">
                        {{ Form::label('facility','Facility') }}
                                            {{ Form::select('facility',$facilities,Input::old('facility'),array('class'=>'form-control','placeholder'=>'Select facility'))}}
                     
                                        </div>

	
                                    </div><!-- /.box-body -->

                                    <div class="box-footer">
					{{ Form::submit('Edit Zone',array('class'=>'btn btn-primary')) }}
                                    </div>

				    {{ Form::close() }}
                      		    </div><!-- /.box -->
				</div>
			</div>
   		</section>	
@stop

@section('script')
    <script type="text/javascript">
    $(document).ready(function(){        
       var address= "/cch/yabr3";    
    // when any option from region list is selected
    $("select[name='region']").change(function(){ 
      // get the selected option value of region
      var optionValue = jQuery("select[name='region']").val();   
        
      $("#district")
        .load(address + '/getDistricts', "region="+optionValue, function(response){
          if(response) {
            $("#district").html(response);
          } 
      });     
    });


     // when any option from district list is selected
    $("select[name='district']").change(function(){ 
      // get the selected option value of region
      var optionValue = jQuery("select[name='district']").val();   
        
      $("#subdistrict")
        .load(address + '/getSubDistricts', "district="+optionValue, function(response){
          if(response) {
            $("#subdistrict").html(response);
          } 
      });     
    });

     // when any option from district list is selected
    $("select[name='district']").change(function(){ 
      // get the selected option value of region
      var optionValue = jQuery("select[name='district']").val();   
        
      $("#facility")
        .load(address + '/getFacilities', "district="+optionValue, function(response){
          if(response) {
            $("#facility").html(response);
          } 
      });     
    });



  });
  </script>
@stop

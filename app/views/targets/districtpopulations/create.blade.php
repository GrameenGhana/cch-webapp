@extends('layouts.master')

@section('head')
   @parent
   {{ HTML::style('css/datatables/dataTables.bootstrap.css'); }} 
@stop

@section('content-header')
        
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1> <i class="fa fa-hospital-o"></i> Districts Population <small>Control panel</small> </h1>
                    <ol class="breadcrumb">
                        <li><a href="{{ URL::to('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="{{ URL::to('targets/population/districts') }}"><i class="fa fa-hospital-o"></i> Districts Population Data</a></li>
                        <li class="active">Create</li>
                    </ol>
                </section>
@stop

@section('content')

                <section class="content invoice">
<!-- title row -->
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header">Add Population Information</h2>                            
                        </div><!-- /.col -->
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="box box-primary">
                          {{ Form::open(array('url'=> 'targets/population/districts','method'=>'post')) }}

                          <div class="box-body">

                           @if(Session::has('flash_error'))
                           <div class="callout callout-danger">
                               <h4>Error!</h4> <br/>
                               {{ HTML::ul($errors->all()) }}
                           </div>
                           @endif

                           <div class="form-group">
                                {{ Form::label('region','Region') }}
                                {{ Form::select('region',$region,Input::old('region'),array('class'=>'form-control','placeholder'=>'Enter Region to which this district belongs'))}}
           
                            </div>


                           <div class="form-group">
                               {{ Form::label('district','District') }}
                               {{ Form::select('district',$districts,Input::old('district'),array('class'=>'form-control','placeholder'=>'Select district'))}}

                           </div>

                           <div class="form-group">
                              <div class="row">
                                <div class="col-md-6">
                                    {{ Form::label('year','Year') }}
                                    {{ Form::select('year', array('2012'=>'2012','2013'=>'2013','2014'=>'2014','2015'=>'2015'), Input::old('year'),array('class'=>'form-control','placeholder'=>'Select year')) }}
                                </div>  
                                <div class="col-md-6">
                                {{ Form::label('population','Population') }}
                                {{ Form::text('population',Input::old('population'),array('class'=>'form-control','placeholder'=>'Enter population')) }}

                               </div> 
                            </div>

                           </div>

                   </div><!-- /.box -->
               </div>
    

           </div>

             <div class="col-md-6">
            <div class="box box-primary">
                            <div class="box-body">
                                <fieldset>
                                    <legend>Distribution Populations (%) </legend>    
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            {{ Form::label('expected_pregnancies','Expected Pregnancies ') }}
                                            {{ Form::text('expected_pregnancies',Input::old('expected_pregnancies'),array('class'=>'form-control','placeholder'=>'')) }}
                                        </div>

                                        <div class="col-md-6 form-group">
                                            {{ Form::label('wifa_15_49_yrs','Wifa 15-49 yrs ') }}
                                            {{ Form::text('wifa_15_49_yrs',Input::old('wifa_15_49_yrs'),array('class'=>'form-control','placeholder'=>'')) }}
                                        </div>
<!--                                    <div class="form-group">
                                        {{ Form::label('chn_6_11_mnths','Chn 6-11 months ') }}
                                        {{ Form::text('chn_6_11_mnths',Input::old('chn_6_11_mnths'),array('class'=>'form-control','placeholder'=>'')) }}
                                    </div>-->

                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            {{ Form::label('chn_0_to_11_mnths','Chn 0-11 months ') }}
                                            {{ Form::text('chn_0_to_11_mnths',Input::old('chn_0_to_11_mnths'),array('class'=>'form-control','placeholder'=>'')) }}
                                        </div>

                                        <div class="col-md-6 form-group">
                                            {{ Form::label('chn_12_23_mnths','Chn 12-23 months') }}
                                            {{ Form::text('chn_12_23_mnths',Input::old('chn_12_23_mnths'),array('class'=>'form-control','placeholder'=>'')) }}
                                        </div>

<!--                                    <div class="form-group">
                                        {{ Form::label('chn_0_to_23_mnths','Chn 0-23 months ') }}
                                        {{ Form::text('chn_0_to_23_mnths',Input::old('chn_0_to_23_mnths'),array('class'=>'form-control','placeholder'=>'')) }}
                                    </div>-->

                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            {{ Form::label('chn_24_to_59_mnths','Chn 24-59 months ') }}
                                            {{ Form::text('chn_24_to_59_mnths',Input::old('chn_24_to_59_mnths'),array('class'=>'form-control','placeholder'=>'')) }}
                                        </div>
                                        <div class="col-md-6 form-group">
                                            {{ Form::label('chn_less_than_5_yrs','Chn less than 5 yrs') }}
                                            {{ Form::text('chn_less_than_5_yrs',Input::old('chn_less_than_5_yrs'),array('class'=>'form-control','placeholder'=>'')) }}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            {{ Form::label('men_women_50_to_60_yrs','Men & Women 50-60 yrs ') }}
                                            {{ Form::text('men_women_50_to_60_yrs',Input::old('men_women_50_to_60_yrs'),array('class'=>'form-control','placeholder'=>'')) }}
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                         <div class="box-footer">
                           {{ Form::submit('Add Population Information',array('class'=>'btn btn-primary')) }}
                       </div>

                       {{ Form::close() }}

           </div>

       </div>
        </section>  
@stop


@section('script')
    <script type="text/javascript">
    $(document).ready(function(){        
    // when any option from country list is selected
    $("select[name='region']").change(function(){ 
      
      
      // get the selected option value of country
      var optionValue = jQuery("select[name='region']").val();   
            
      /**
       * pass country value through GET method as query string
       * the 'status' parameter is only a dummy parameter (just to show how multiple parameters can be passed)
       * if we get response from data.php, then only the cityAjax div is displayed 
       * otherwise the cityAjax div remains hidden
       */
      $("#district")
        .load('/cch/yabr3/getDistricts', "region="+optionValue, function(response){
          if(response) {
            $("#district").html(response);
          } 
      });     
    });
  });
  </script>
@stop

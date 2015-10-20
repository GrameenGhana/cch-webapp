@extends('layouts.master')

@section('head')
   @parent
   {{ HTML::style('css/datatables/dataTables.bootstrap.css'); }} 
@stop

@section('content-header')
        
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1> <i class="fa fa-hospital-o"></i> Zone Target <small>Control panel</small> </h1>
                    <ol class="breadcrumb">
                        <li><a href="{{ URL::to('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="{{ URL::to('zonepopulations') }}"><i class="fa fa-hospital-o"></i> Zone  Target</a></li>
                        <li class="active">Edit</li>
                    </ol>
                </section>
@stop

@section('content')

                <section class="content invoice">
<!-- title row -->
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header">Update Update</h2>                            
                        </div><!-- /.col -->
                    </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="box box-primary">
                   {{ Form::open(array('url'=> 'zonepopulations/'.$pop->id,'method'=>'put')) }}

                                    <div class="box-body">
                    @if(Session::has('flash_error'))
                    <div class="callout callout-danger">
                                            <h4>Error!</h4> <br/>
                        {{ HTML::ul($errors->all()) }}
                                        </div>
                    @endif

                            <div class="form-group">
                                {{ Form::label('region','Region') }}
                                {{ Form::select('region',$region,$pop->region,array('class'=>'form-control','placeholder'=>'Enter Region to which this district belongs'))}}
           
                            </div>


                           <div class="form-group">
                               {{ Form::label('district','District') }}
                               {{ Form::select('district',$districts,$pop->district_id,array('class'=>'form-control','placeholder'=>'Select district'))}}

                           </div>

                           <div class="form-group">
                                {{ Form::label('subdistrict','SubDistrict') }}
                                {{ Form::select('subdistrict',$subdistricts,$pop->subdistrict_id,array('class'=>'form-control','placeholder'=>'Select sub district'))}}
                     
                            </div>
    
                            <div class="form-group">
                               {{ Form::label('zoneselected','Zone') }}
                               {{ Form::select('zoneselected',$zones,$pop->zone_id,array('class'=>'form-control','placeholder'=>'Select zone'))}}

                           </div>
                           <div class="form-group">
                              <div class="row">
                                <div class="col-md-6">
                                    {{ Form::label('year','Year') }}
                                    {{ Form::select('year', array('2012'=>'2012','2013'=>'2013','2014'=>'2014','2015'=>'2015'), $pop->year,array('class'=>'form-control','placeholder'=>'Select year')) }}
                                </div>  <div class="col-md-6">
                                {{ Form::label('population','Population') }}
                                {{ Form::text('population',$pop->population,array('class'=>'form-control','placeholder'=>'Enter population')) }}

                            </div> </div>

                            <div class="form-group">
                                {{ Form::label('district_percentage','Percentage of district (%)') }}
                                {{ Form::text('district_percentage',$pop->district_percentage,array('class'=>'form-control','placeholder'=>'Enter percentage')) }}
                            </div>
                                    </div><!-- /.box-body -->


                   </div><!-- /.box -->
               </div>

                 </div>

             <div class="col-md-6">
            <div class="box box-primary">
                            <div class="box-body">
                                <fieldset>
                                    <legend>Distribution Populations</legend>    
                                    <div class="form-group">
                                        {{ Form::label('expected_pregnancies','Expected Pregnancies on 4%') }}
                                        {{ Form::text('expected_pregnancies',$pop->expected_pregnancies,array('class'=>'form-control','placeholder'=>'')) }}
                                    </div>

                                    <div class="form-group">
                                        {{ Form::label('chn_6_11_mnths','Chn 6-11 months') }}
                                        {{ Form::text('chn_6_11_mnths',$pop->chn_6_11_mnths,array('class'=>'form-control','placeholder'=>'')) }}
                                    </div>

                                    <div class="form-group">
                                        {{ Form::label('chn_0_to_11_mnths','Chn 0-11 months') }}
                                        {{ Form::text('chn_0_to_11_mnths',$pop->chn_0_to_11_mnths,array('class'=>'form-control','placeholder'=>'')) }}
                                    </div>

                                    <div class="form-group">
                                        {{ Form::label('chn_12_23_mnths','Chn 12-23 months') }}
                                        {{ Form::text('chn_12_23_mnths',$pop->chn_12_23_mnths,array('class'=>'form-control','placeholder'=>'')) }}
                                    </div>

                                    <div class="form-group">
                                        {{ Form::label('chn_0_to_23_mnths','Chn 0-23 months') }}
                                        {{ Form::text('chn_0_to_23_mnths',$pop->chn_0_to_23_mnths,array('class'=>'form-control','placeholder'=>'')) }}
                                    </div>

                                    <div class="form-group">
                                        {{ Form::label('chn_24_to_59_mnths','Chn 24-59 months') }}
                                        {{ Form::text('chn_24_to_59_mnths',$pop->chn_24_to_59_mnths,array('class'=>'form-control','placeholder'=>'')) }}
                                    </div>

                                    <div class="form-group">
                                        {{ Form::label('chn_less_than_5_yrs','Chn < 5 yrs') }}
                                        {{ Form::text('chn_less_than_5_yrs',$pop->chn_less_than_5_yrs,array('class'=>'form-control','placeholder'=>'')) }}
                                    </div>

                                    <div class="form-group">
                                        {{ Form::label('wifa_15_49_yrs','Wifa 15-49 yrs') }}
                                        {{ Form::text('wifa_15_49_yrs',$pop->wifa_15_49_yrs,array('class'=>'form-control','placeholder'=>'')) }}
                                    </div>

                                    <div class="form-group">
                                        {{ Form::label('men_women_50_to_60_yrs','Men & Women 50-60 yrs') }}
                                        {{ Form::text('men_women_50_to_60_yrs',$pop->men_women_50_to_60_yrs,array('class'=>'form-control','placeholder'=>'')) }}
                                    </div>




                                </fieldset>
                            </div>
                        </div>


                                    <div class="box-footer">
                    {{ Form::submit('Update Population',array('class'=>'btn btn-primary')) }}
                                    </div>

                    {{ Form::close() }}
                </div>
            </div>
        </section>  
@stop

@section('script')
    <script type="text/javascript">
    $(document).ready(function()
    {
	 var address= "/cch/yabr3";
     // when any option from region list is selected
    $("select[name='region']").change(function(){ 
      // get the selected option value of region
      var optionValue = jQuery("select[name='region']").val();   
        
      $("#district")
        .load(address +'/getDistricts', "region="+optionValue, function(response){
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
    $("select[name='subdistrict']").change(function(){ 
      // get the selected option value of region
      var optionValue = jQuery("select[name='subdistrict']").val();   
        
      $("#zoneselected")
        .load(address + '/getZones', "subdistrict="+optionValue, function(response){
          if(response) {
            $("#zoneselected").html(response);
          } 
      });     
    });


    var totalpopulation = "",
    expected_pregnancies_percentage ="",
    chn_6_11_mnths_percentage="",
    chn_0_to_11_mnths_percentage="",
    chn_12_23_mnths_percentage="",
    chn_0_to_23_mnths_percentage="",
    chn_24_to_59_mnths_percentage="",
    chn_less_than_5_yrs_percentage="",
    wifa_15_49_yrs_percentage="",
    men_women_50_to_60_yrs_percentage="";


    function getDistrictTotalPopulation(){
      var district = $("#district").val();
      var year = $("#year").val();
      var district_percentage = $("#district_percentage").val();

      $.ajax({
            type: "GET",
            cache: false,
            dataType:"json",
            url : address + "/getDistrictTotalPopulationZones",
            data: { district : district , y : year , percentage : district_percentage   },
            success: function(data) {

              totalpopulation = data.population;
              expected_pregnancies_percentage = data.expected_pregnancies;
              chn_6_11_mnths_percentage  = data.chn_6_11_mnths ;
              chn_0_to_11_mnths_percentage = data.chn_0_to_11_mnths ;
              chn_12_23_mnths_percentage = data.chn_12_23_mnths ;
              chn_0_to_23_mnths_percentage = data.chn_0_to_23_mnths ;
              chn_24_to_59_mnths_percentage = data.chn_24_to_59_mnths ;
              chn_less_than_5_yrs_percentage = data.chn_less_than_5_yrs ;
              wifa_15_49_yrs_percentage = data.wifa_15_49_yrs ;
              men_women_50_to_60_yrs_percentage = data.men_women_50_to_60_yrs ;

              console.log("Total Population of district -> " + totalpopulation);
              console.log("Expected Pregnancies -> " + expected_pregnancies_percentage);

              $('#expected_pregnancies_percentage').html(" on " +expected_pregnancies_percentage + "%");
              $('#chn_6_11_mnths_percentage').html(" on " +chn_6_11_mnths_percentage + "%");
              $('#chn_0_to_11_mnths_percentage').html(" on " +chn_0_to_11_mnths_percentage + "%");
              $('#chn_12_23_mnths_percentage').html(" on " +chn_12_23_mnths_percentage + "%");
              $('#chn_0_to_23_mnths_percentage').html(" on " +chn_0_to_23_mnths_percentage + "%");
              $('#chn_24_to_59_mnths_percentage').html(" on " +chn_24_to_59_mnths_percentage + "%");
              $('#chn_less_than_5_yrs_percentage').html(" on " +chn_less_than_5_yrs_percentage + "%");
              $('#wifa_15_49_yrs_percentage').html(" on " +wifa_15_49_yrs_percentage + "%");
              $('#men_women_50_to_60_yrs_percentage').html(" on " +men_women_50_to_60_yrs_percentage + "%");


               var population = ( district_percentage / 100 ) * totalpopulation;
               $("#population").val(population.toFixed(0));
              
                 updatePopulations();

               
            }
        })
        .done(function(data) {
           // alert('done');
        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            alert('No population set for selected district');
        });

    }

    $(document).on("change, keyup", "#district_percentage", getDistrictTotalPopulation);

    function getSubDistrictTotalPopulation(){
      var district = $("#subdistrict").val();
      var year = $("#year").val();
      var population = $("#population").val();

      $.ajax({
            type: "GET",
            cache: false,
            dataType:"json",
            url : address + "/getSubDistrictTotalPopulation",
            data: { subdistrict : subdistrict , y : year , population : population   },
            success: function(data) {

              totalpopulation = data.population;

              console.log("Total Population of subdistrict -> " + totalpopulation);

              return true;
            }
        })
        .done(function(data) {
           // alert('done');
        })
        .fail(function(jqXHR, ajaxOptions, thrownError) {
            return false;
        });

    }



    function updatePopulations()
    {

     // if(getSubDistrictTotalPopulation()){ //if population is not greater than totals of subdistrict
                   
                
        var population = parseFloat($("#population").val());
        var expected_pregnancies = population * ( expected_pregnancies_percentage / 100 );
        var chn_6_11_mnths = population * ( chn_6_11_mnths_percentage / 100 );
        var chn_0_to_11_mnths = population * ( chn_0_to_11_mnths_percentage / 100 );
        var chn_12_23_mnths = population * ( chn_12_23_mnths_percentage / 100 );
        var chn_0_to_23_mnths = population * ( chn_0_to_23_mnths_percentage / 100 );
        var chn_24_to_59_mnths = population * ( chn_24_to_59_mnths_percentage / 100 );
        var chn_less_than_5_yrs = population * ( chn_less_than_5_yrs_percentage / 100 );
        var wifa_15_49_yrs = population * ( wifa_15_49_yrs_percentage / 100 );
        var men_women_50_to_60_yrs = population * ( men_women_50_to_60_yrs_percentage / 100 );

        expected_pregnancies = expected_pregnancies.toFixed(0);
        chn_6_11_mnths = chn_6_11_mnths.toFixed(0);
        chn_0_to_11_mnths = chn_0_to_11_mnths.toFixed(0);
        chn_12_23_mnths = chn_12_23_mnths.toFixed(0);
        chn_0_to_23_mnths = chn_0_to_23_mnths.toFixed(0);
        chn_24_to_59_mnths = chn_24_to_59_mnths.toFixed(0);
        chn_less_than_5_yrs = chn_less_than_5_yrs.toFixed(0);
        wifa_15_49_yrs = wifa_15_49_yrs.toFixed(0);
        men_women_50_to_60_yrs = men_women_50_to_60_yrs.toFixed(0);

        $("#expected_pregnancies").val(expected_pregnancies);
        $("#chn_6_11_mnths").val(chn_6_11_mnths);
        $("#chn_0_to_11_mnths").val(chn_0_to_11_mnths);
        $("#chn_12_23_mnths").val(chn_12_23_mnths);
        $("#chn_0_to_23_mnths").val(chn_0_to_23_mnths);
        $("#chn_24_to_59_mnths").val(chn_24_to_59_mnths);
        $("#chn_less_than_5_yrs").val(chn_less_than_5_yrs);
        $("#wifa_15_49_yrs").val(wifa_15_49_yrs);
        $("#men_women_50_to_60_yrs").val(men_women_50_to_60_yrs);

      //  }else{
       //             alert('Population do not tally with totals of populations of subdistrict selected');
      //          }
        
    }
    $(document).on("change, keyup", "#population", updatePopulations);
   });
  </script>
@stop


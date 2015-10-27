@extends('layouts.master')

@section('head')
   @parent
   {{ HTML::style('css/datatables/dataTables.bootstrap.css'); }} 
@stop

@section('content-header')
        
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1> <i class="fa fa-hospital-o"></i> District Target <small>Control panel</small> </h1>
                    <ol class="breadcrumb">
                        <li><a href="{{ URL::to('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">District  Target</li>
                    </ol>
                </section>
@stop

@section('content')

                <section class="content invoice">
<!-- title row -->
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header">
                    <a class="btn btn-small btn-success" href="{{ URL::to('districtpopulations/create') }}"><i class="fa fa-plus-circle"></i> Add a Population</a>
                            </h2>                            
                        </div><!-- /.col -->
                    </div>
                    @if (Session::has('message'))
                        <div class="alert alert-info">{{ Session::get('message') }}</div>
                    @endif

                    <div class="box">
                                <div class="box-header">
                             <label>Select year : </label>
                                  <select name="year" onchange="if (this.value) window.location.href=this.value">
                                    <option value="/cch/yabr3/getDistrictPopulationData?year=2015">2015</option>
                                    <option value="/cch/yabr3/getDistrictPopulationData?year=2014">2014</option>
                                    <option value="/cch/yabr3/getDistrictPopulationData?year=2013">2013</option>
                                    <option value="/cch/yabr3/getDistrictPopulationData?year=2012">2012</option>
                                  </select>
				<center><label> Showing population for the year of <h2>{{$year}}</h2> </label></center>    
			    </div><!-- /.box-header -->
                                <div class="box-body table-responsive">
                                    <table id="factable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>District</th>
                                                <th>Year</th>

                                                <th>Population</th>
                                                 @if (count($pops)==1)

    							<th>Expected Pregnancies ({{ $pops[0]->expected_pregnancies }}%)</th>
                                                <!--<th>Chn 6-11 months ({{ $pops[0]->chn_6_11_mnths }} %)</th>--> 
                                                <th>Chn 0-11 months ({{ $pops[0]->chn_0_to_11_mnths }}%)</th>
                                                <th>Chn 12-23 months ({{ $pops[0]->chn_12_23_mnths }}%)</th>
                                                <!--<th>Chn 0-23 months ({{ $pops[0]->chn_0_to_23_mnths }}%)</th>-->
                                                <th>Chn 24-59 months ({{ $pops[0]->chn_24_to_59_mnths }}%)</th>
                                                <th>Chn < 5 yrs ({{ $pops[0]->chn_less_than_5_yrs }}%)</th>
                                                <th>Wifa 15-49 yrs ( {{ $pops[0]->wifa_15_49_yrs }}%)</th>
                                                <th>Men & Women 50-60 yrs ({{ $pops[0]->men_women_50_to_60_yrs }}%)</th>

@endif

@if (count($pops)!=1)
    						<th>Expected Pregnancies </th>
                                                <!--<th>Chn 6-11 months </th>-->
                                                <th>Chn 0-11 months </th>
                                                <th>Chn 12-23 months </th>
                                                <!--<th>Chn 0-23 months </th>-->
                                                <th>Chn 24-59 months </th>
                                                <th>Chn < 5 yrs </th>
                                                <th>Wifa 15-49 yrs </th>
                                                <th>Men & Women 50-60 yrs </th>



@endif                                                
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                    @foreach($pops as $k => $value) 
                         <tr>
                          
                          <td> {{ $value->district->name}} </td>
                          <td> {{ $value->year}} </td>
                          <td> {{ $value->population }} </td>

 @if (count($pops)==1)
 			 <td> {{ round(($value->expected_pregnancies / 100 ) * $value->population) }}  </td>
                          <!--<td> {{ round(($value->chn_6_11_mnths / 100 ) * $value->population) }} </td>-->
                          <td> {{ round(($value->chn_0_to_11_mnths / 100 ) * $value->population) }} </td>
                          <td> {{ round(($value->chn_12_23_mnths / 100 ) * $value->population) }} </td>
                          <!--<td> {{ round(($value->chn_0_to_23_mnths / 100 ) * $value->population) }} </td>-->
                          <td> {{ round(($value->chn_24_to_59_mnths / 100 ) * $value->population) }} </td>
                          <td> {{ round(($value->chn_less_than_5_yrs / 100 ) * $value->population) }} </td>
                          <td> {{ round(($value->wifa_15_49_yrs / 100 ) * $value->population) }} </td>
                          <td> {{ round(($value->men_women_50_to_60_yrs / 100 ) * $value->population) }} </td>
@endif 

@if (count($pops)!=1)
                          <td> {{ round(($value->expected_pregnancies / 100 ) * $value->population) }} ({{ $value->expected_pregnancies }}%) </td>
                          <!--<td> {{ round(($value->chn_6_11_mnths / 100 ) * $value->population) }} ({{ $value->chn_6_11_mnths }}%)</td>-->
                          <td> {{ round(($value->chn_0_to_11_mnths / 100 ) * $value->population) }} ({{ $value->chn_0_to_11_mnths }}%)</td>
                          <td> {{ round(($value->chn_12_23_mnths / 100 ) * $value->population) }} ({{ $value->chn_12_23_mnths }}%)</td>
                          <!--<td> {{ round(($value->chn_0_to_23_mnths / 100 ) * $value->population) }} ({{ $value->chn_0_to_23_mnths }}%)</td>-->
                          <td> {{ round(($value->chn_24_to_59_mnths / 100 ) * $value->population) }} ({{ $value->chn_24_to_59_mnths }}%)</td>
                          <td> {{ round(($value->chn_less_than_5_yrs / 100 ) * $value->population) }} ({{ $value->chn_less_than_5_yrs }}%)</td>
                          <td> {{ round(($value->wifa_15_49_yrs / 100 ) * $value->population) }} ( {{ $value->wifa_15_49_yrs }}%)</td>
                          <td> {{ round(($value->men_women_50_to_60_yrs / 100 ) * $value->population) }} ({{ $value->men_women_50_to_60_yrs }}%)</td>                          

@endif
                          <td>

                            <a title="Edit" class="btn btn-sm btn-info" href="{{ URL::to('districtpopulations/' . $value->id . '/edit') }}"><i class="fa fa-pencil"></i></a>
                           
                            <a title="Bulk Edit Sub District Zones Population" class="btn btn-sm btn-info"
 href="{{ URL::to('/subdistrictpopulations/bulkedit/' . $value->district_id . '/'.$value->year) }}"><i class="fa fa-file">Sub District Targets</i></a>
                            
                            <!--
                            <a class="btn btn-sm btn-danger" href="{{ URL::to('districts/' . $value->id . '/delete') }}">Delete</a>
                            -->
                          </td>
                         </tr>
                    @endforeach
                    </tbody>
                     </table>
                </div>
            </div>
        </section>  
@stop


@section('script')
    <script type="text/javascript">
            $(function() {
                $('#factable').dataTable({
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bSort": true,
                    "bInfo": true,
                    "bAutoWidth": false
                });

		var year = {{ $year}};
            console.log('Year => ' + year);

            switch (year) {
              case 2015:
              $('select>option:eq(0)').attr('selected', true);
              break;
              case 2014:
              $('select>option:eq(1)').attr('selected', true);
              break;
              case 2013:
              $('select>option:eq(2)').attr('selected', true);
              break;
              case 2012:
              $('select>option:eq(3)').attr('selected', true);
              break;


            }
            });
        </script>
@stop


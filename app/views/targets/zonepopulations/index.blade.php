@extends('layouts.master')

@section('head')
   @parent
   {{ HTML::style('css/datatables/dataTables.bootstrap.css'); }} 
   <style type="text/css">
      #factable_filter { margin-top:-25px;}
   </style>

@stop

@section('content-header')
        
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1> <i class="fa fa-hospital-o"></i> Zones Population Data </h1>
                    <ol class="breadcrumb">
                        <li><a href="{{ URL::to('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Zones Population Data</li>
                    </ol>
                </section>
@stop

@section('content')

                <section class="content">
<!-- title row -->
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header">
                                <a class="btn btn-small btn-success" href="{{ URL::to('targets/population/zones/create') }}"><i class="fa fa-plus-circle"></i> Add Zone Population Data</a>
                            </h2>                            
                        </div><!-- /.col -->
                    </div>
                    @if (Session::has('message'))
                        <div class="alert alert-info">{{ Session::get('message') }}</div>
                    @endif

                    <div class="panel">
                                <div class="box-body table-responsive" style="padding:10px">

                                    <div style="">
				                        Showing population for 
                                        <select name="year" style="font-size:20px;" onchange="if (this.value) window.location.href=this.value">
                                            <option value="/cch/yabr3/targets/population/zones?year=2015">2015</option>
                                            <option value="/cch/yabr3/targets/population/zones?year=2014">2014</option>
                                            <option value="/cch/yabr3/targets/population/zones?year=2013">2013</option>
                                            <option value="/cch/yabr3/targets/population/zones?year=2012">2012</option>
                                        </select>
 				                    </div>


                                    <table id="factable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Zone</th>
                                                <th>Population</th>
                                                <th>Percentage of District</th>
                                                <th>Expected Pregnancies</th>
                                                <th>Chn 6-11 months</th>
                                                <th>Chn 0-11 months</th>
                                                <th>Chn 12-23 months</th>
                                                <th>Chn 0-23 months</th>
                                                <th>Chn 24-59 months</th>
                                                <th>Chn < 5 yrs</th>
                                                <th>Wifa 15-49 yrs</th>
                                                <th>Men & Women 50-60 yrs</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                    @foreach($zones as $k => $value) 
                         <tr>
                          <td> {{ $value->zone->name}} </td>
                          <td> {{ $value->population }} </td>
                          <td> {{ $value->district_percentage }} </td>
                          <td> {{ $value->expected_pregnancies }} </td>
                          <td> {{ $value->chn_6_11_mnths }} </td>
                          <td> {{ $value->chn_0_to_11_mnths }} </td>
                          <td> {{ $value->chn_12_23_mnths }} </td>
                          <td> {{ $value->chn_0_to_23_mnths }} </td>
                          <td> {{ $value->chn_24_to_59_mnths }} </td>
                          <td> {{ $value->chn_less_than_5_yrs }} </td>
                          <td> {{ $value->wifa_15_49_yrs }} </td>
                          <td> {{ $value->men_women_50_to_60_yrs }} </td>
                          <td>
                            <a title="Edit" class="btn btn-sm btn-info" href="{{ URL::to('targets/population/zones/' . $value->id . '/edit') }}"><i class="fa fa-pencil"></i></a>
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


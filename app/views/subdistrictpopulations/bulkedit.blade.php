@extends('layouts.master')

@section('head')
@parent
{{ HTML::style('css/datatables/dataTables.bootstrap.css'); }} 
@stop

@section('content-header')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1> <i class="fa fa-hospital-o"></i>Sub Districts Populations in {{$district->name}} <small>Control panel</small> </h1>
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Sub District Populations &raquo; {{$district->name}} - <strong>{{$year}}</strong></li>
    </ol>
</section>
@stop

@section('content')

<section class="content invoice">
    <!-- title row -->
    <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">
<!--                <a class="btn btn-small btn-success" href="{{ URL::to('subdistrictpopulations/create') }}"><i class="fa fa-plus-circle"></i> Add a Population</a>
    -->        </h2>                            
        </div><!-- /.col -->
    </div>
    @if (Session::has('message'))
    <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif

    <div class="box">
        <div class="box-header">
        </div><!-- /.box-header -->
        <div class="box-body table-responsive">
            {{ Form::open(array('url'=> 'subdistrictpopulations/bulkedit','method'=>'post')) }}
            <input type="submit" value="Save All"  class="btn btn-success btn-lg" />
  
            <input type="hidden" name="year" value="{{$year}}" /> 
	    <input type="hidden" name="type" value="{{$type}}" />
            <input type="hidden" name="typeId" value="{{$typeId}}" />


            Sub District Populations &raquo; {{$district->name}} - <strong>{{$year}}</strong>
            <table id="bulkedittable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Sub District</th>
                        <th>Year</th>
                        <th>Percentage Of Dist</th>
                        <th>Population<br />
{{ $districtpopulation->population }}
</th>
                        <th>Expected Pregnancies<br />
                            {{ $districtpopulation->expected_pregnancies }}%
                        </th>
                        <th>Chn 6-11 months<br />
                            {{ $districtpopulation->chn_6_11_mnths }}%</th>
                        <th>Chn 0-11 months<br />
                            {{ $districtpopulation->chn_0_to_11_mnths }}%</th>
                        <th>Chn 12-23 months<br />
                            {{ $districtpopulation->chn_12_23_mnths }}%</th>
                        <th>Chn 0-23 months<br />
                            {{ $districtpopulation->chn_0_to_23_mnths }}%</th>
                        <th>Chn 24-59 months<br />
                            {{ $districtpopulation->chn_24_to_59_mnths }}%</th>
                        <th>Chn < 5 yrs<br />
                            {{ $districtpopulation->chn_less_than_5_yrs }}%</th>
                        <th>Wifa 15-49 yrs<br />
                            {{ $districtpopulation->wifa_15_49_yrs }}%</th>
                        <th>Men & Women 50-60 yrs<br />
                            {{ $districtpopulation->men_women_50_to_60_yrs }}%</th> 
 			<th></th>


                    </tr>
                </thead>
                <tbody>
                    @foreach($subdistricts as $k => $value) 
                    <tr>

                        <td> {{ $value->subdistrict->name }}  
                            <input type="hidden" name="sp_id_{{$value->id}}" value="{{$value->id}}" />
                        </td>
                        <td> {{ $value->year}} </td>
                        <td> {{ Form::text('district_percentage_'.$value->id,$value->district_percentage,array('class'=>'form-control','placeholder'=>'% ','onkeyup'=>'checkPopulation('.$value->id.')','id'=>'district_percentage_'.$value->id))  }}</td>
                        <td> {{ Form::text('population_'.$value->id,$value->population,array('class'=>'form-control','placeholder'=>''.$value->name ,'onkeyup'=>'updateCol(4)','id'=>'population_'.$value->id))}}</td>
                        <td> {{ Form::text('expected_pregnancies_'.$value->id,$value->expected_pregnancies,array('class'=>'form-control','placeholder'=>''.$value->name,'onkeyup'=>'updateCol(5)' ,'id'=>'expected_pregnancies_'.$value->id))}}</td>
                        <td> {{ Form::text('chn_6_11_mnths_'.$value->id,$value->chn_6_11_mnths,array('class'=>'form-control','placeholder'=>$value->name ,'onkeyup'=>'updateCol(6)','id'=>'chn_6_11_mnths_'.$value->id))}}</td>
                        <td> {{ Form::text('chn_0_to_11_mnths_'.$value->id,$value->chn_0_to_11_mnths,array('class'=>'form-control','placeholder'=>''.$value->name ,'onkeyup'=>'updateCol(7)','id'=>'chn_0_to_11_mnths_'.$value->id))}}</td>
                        <td> {{ Form::text('chn_12_23_mnths_'.$value->id,$value->chn_12_23_mnths,array('class'=>'form-control','placeholder'=>''.$value->name,'onkeyup'=>'updateCol(8)','id'=>'chn_12_23_mnths_'.$value->id ))}}</td>
                        <td> {{ Form::text('chn_0_to_23_mnths_'.$value->id,$value->chn_0_to_23_mnths,array('class'=>'form-control','placeholder'=>''.$value->name,'onkeyup'=>'updateCol(9)','id'=>'chn_0_to_23_mnths_'.$value->id))}}</td>
                        <td> {{ Form::text('chn_24_to_59_mnths_'.$value->id,$value->chn_24_to_59_mnths,array('class'=>'form-control','placeholder'=> $value->name,'onkeyup'=>'updateCol(10)','id'=>'chn_24_to_59_mnths_'.$value->id)) }}</td>
                        <td> {{ Form::text('chn_less_than_5_yrs_'.$value->id,$value->chn_less_than_5_yrs,array('class'=>'form-control','placeholder'=>$value->name,'onkeyup'=>'updateCol(11)','id'=>'chn_less_than_5_yrs_'.$value->id)) }}</td>
                        <td> {{ Form::text('wifa_15_49_yrs_'.$value->id,$value->wifa_15_49_yrs,array('class'=>'form-control','placeholder'=>$value->name,'onkeyup'=>'updateCol(12)','id'=>'wifa_15_49_yrs_'.$value->id)) }}</td>
                        <td> {{ Form::text('men_women_50_to_60_yrs_'.$value->id,$value->men_women_50_to_60_yrs,array('class'=>'form-control','placeholder'=>$value->name,'onkeyup'=>'updateCol(13)','id'=>'men_women_50_to_60_yrs_'.$value->id)) }}</td>
			<td> <a title="Sub District Zones  Targets Bulk Edit" class="btn btn-sm btn-info" 
href="{{ URL::to('/zonepopulations/bulkedit/subdistrict/' . $value->subdistrict->id . '/'.$year) }}"><i class="fa fa-book">Zones Target</i></td>

                    </tr>
                    @endforeach


  <tr>
                        <td colspan='2'>Your District Totals</td>
                        
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                               
        </tr>
 <tr>
                        <td colspan='2'><strong>District Totals</strong> {{$district->name}}-{{$year}}</td>

                        <td>100</td>
                        <td>{{ $districtpopulation->population }}</td>
                        <td>{{ number_format(($districtpopulation->expected_pregnancies/100)* $districtpopulation->population ) }}</td>
                        <td>{{ number_format(($districtpopulation->chn_6_11_mnths/100)* $districtpopulation->population ) }}</td>
                        <td>{{ number_format(($districtpopulation->chn_0_to_11_mnths/100)* $districtpopulation->population ) }}</td>
                        <td>{{ number_format(($districtpopulation->chn_12_23_mnths/100)* $districtpopulation->population ) }}</td>
                        <td>{{ number_format(($districtpopulation->chn_0_to_23_mnths/100)* $districtpopulation->population ) }}</td>
                        <td>{{ number_format(($districtpopulation->chn_24_to_59_mnths/100)* $districtpopulation->population ) }}</td>
                        <td>{{ number_format(($districtpopulation->chn_less_than_5_yrs/100)* $districtpopulation->population ) }}</td>
                        <td>{{ number_format(($districtpopulation->wifa_15_49_yrs/100)* $districtpopulation->population ) }}</td>
                        <td>{{ number_format(($districtpopulation->men_women_50_to_60_yrs/100)* $districtpopulation->population ) }}</td>
		     </tr>

                </tbody>

            </table>
            {{ Form::close() }}
        </div>
    </div>
</section>  
@stop


@section('script')
<script type="text/javascript">
    var DISTRICT_POPULATION = {{ $districtpopulation->population }};
            var expected_pregnancies_percentage = {{ $districtpopulation->expected_pregnancies }};
            var chn_6_11_mnths_percentage = {{ $districtpopulation->chn_6_11_mnths }};
            var chn_0_to_11_mnths_percentage = {{ $districtpopulation->chn_0_to_11_mnths }};
            var chn_12_23_mnths_percentage = {{ $districtpopulation->chn_12_23_mnths }};
            var chn_0_to_23_mnths_percentage = {{ $districtpopulation->chn_0_to_23_mnths }};
            var chn_24_to_59_mnths_percentage = {{ $districtpopulation->chn_24_to_59_mnths }};
            var wifa_15_49_yrs_percentage = {{ $districtpopulation->wifa_15_49_yrs }};
            var men_women_50_to_60_yrs_percentage = {{ $districtpopulation->men_women_50_to_60_yrs }};
            var chn_less_than_5_yrs_percentage = {{ $districtpopulation->chn_less_than_5_yrs}};
            
    
    function checkPopulation(id)
            {


            var dist = DISTRICT_POPULATION * (parseFloat($("#district_percentage_" + id).val())/100);
                    var population = dist.toFixed(0);
                    var expected_pregnancies = population * (expected_pregnancies_percentage / 100);
                    var chn_6_11_mnths = population * (chn_6_11_mnths_percentage / 100);
                    var chn_0_to_11_mnths = population * (chn_0_to_11_mnths_percentage / 100);
                    var chn_12_23_mnths = population * (chn_12_23_mnths_percentage / 100);
                    var chn_0_to_23_mnths = population * (chn_0_to_23_mnths_percentage / 100);
                    var chn_24_to_59_mnths = population * (chn_24_to_59_mnths_percentage / 100);
                    var chn_less_than_5_yrs = population * (chn_less_than_5_yrs_percentage / 100);
                    var wifa_15_49_yrs = population * (wifa_15_49_yrs_percentage / 100);
                    var men_women_50_to_60_yrs = population * (men_women_50_to_60_yrs_percentage / 100);
                    expected_pregnancies = expected_pregnancies.toFixed(0);
                    chn_6_11_mnths = chn_6_11_mnths.toFixed(0);
                    chn_0_to_11_mnths = chn_0_to_11_mnths.toFixed(0);
                    chn_12_23_mnths = chn_12_23_mnths.toFixed(0);
                    chn_0_to_23_mnths = chn_0_to_23_mnths.toFixed(0);
                    chn_24_to_59_mnths = chn_24_to_59_mnths.toFixed(0);
                    chn_less_than_5_yrs = chn_less_than_5_yrs.toFixed(0);
                    wifa_15_49_yrs = wifa_15_49_yrs.toFixed(0);
                    men_women_50_to_60_yrs = men_women_50_to_60_yrs.toFixed(0);
                    $("#population_" + id).val(population);
                    $("#expected_pregnancies_" + id).val(expected_pregnancies);
                    $("#chn_6_11_mnths_" + id).val(chn_6_11_mnths);
                    $("#chn_0_to_11_mnths_" + id).val(chn_0_to_11_mnths);
                    $("#chn_12_23_mnths_" + id).val(chn_12_23_mnths);
                    $("#chn_0_to_23_mnths_" + id).val(chn_0_to_23_mnths);
                    $("#chn_24_to_59_mnths_" + id).val(chn_24_to_59_mnths);
                    $("#chn_less_than_5_yrs_" + id).val(chn_less_than_5_yrs);
                    $("#wifa_15_49_yrs_" + id).val(wifa_15_49_yrs);
                    $("#men_women_50_to_60_yrs_" + id).val(men_women_50_to_60_yrs);
           
showSummations();	
 }



function toFixed(value, precision) {
    var precision = precision || 0,
            power = Math.pow(10, precision),
            absValue = Math.abs(Math.round(value * power)),
            result = (value < 0 ? '-' : '') + String(Math.floor(absValue / power));

    if (precision > 0) {
        var fraction = String(absValue % power),
                padding = new Array(Math.max(precision - fraction.length, 0) + 1).join('0');
        result += '.' + padding + fraction;
    }
    return result;
}
    function sumOfColumns(tableID, columnIndex, hasHeader, hasInput) {


var colsTolast=2;
            var tot = 0;
            $("#" + tableID + " tr" + (hasHeader ? ":gt(0)" : ""))
            .children("td:nth-child(" + columnIndex + ")")
            .each(function () {

            t = parseFloat($(this).children("input").first().val());
                    if (!isNaN(t))
                    tot += parseFloat(t);
            });
            $("#" + tableID + " tr:nth-last-child("+colsTolast+")").children("td:nth-child(" + (columnIndex-1) + ")").html(toFixed(tot));
            return tot;
    }



function updateCol(col)
    {
        sumOfColumns("bulkedittable", col, true, true);
    }


function showSummations(){
            updateCol(3);
            updateCol(4);
            updateCol(5);
            updateCol(6);
            updateCol(7);
            updateCol(8);
            updateCol(9);
            updateCol(10);
            updateCol(11);
            updateCol(12);
            updateCol(13);
          //  updateCol(14);


    }

    showSummations();
//    sumOfColumns("bulkedittable", 3, true, true);

    $(document).ready(function() {

    $('#factable').dataTable({
    "bPaginate": true,
            "bLengthChange": false,
            "bFilter": true,
            "bSort": true,
            "bInfo": true,
            "bAutoWidth": false
    });
    });
</script>
@stop





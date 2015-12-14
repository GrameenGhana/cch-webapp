@extends('layouts.master')

@section('head')
   @parent
   {{ HTML::style('css/datatables/dataTables.bootstrap.css'); }} 
   <style type="text/css">
      #factable_filter { margin-top:-25px;}

      input:focus, textarea:focus {
        background-color: #FFFFCC;
      }
   </style>

@stop

@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <i class="fa fa-hospital-o"></i> Zones Indicators Actuals </h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Zones Indicators Actuals</li>
        </ol>
    </section>
@stop

@section('content')

     <section class="content">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                 @if (Session::has('message'))
                        <div class="alert alert-info">{{ Session::get('message') }}</div>
                 @endif
            </div>
         </div>

         <div class="panel">
            <div class="box-body table-responsive" style="padding:10px">
                <div class="row" style="padding-bottom:40px;">
                    <div class="col-md-12 text-center">
				        <label>Zone</label> 
                        <select name="location" id="location" style="margin-right: 20px;" onchange="updateTable()">
                         @foreach($locations as $i => $district) 
                            <optgroup label="{{ $district->name }}">
                            @foreach($district->subdistricts as $k => $sd) 
                                <optgroup label="{{ $sd->name }}">
                                @foreach($sd->zones as $l => $z) <option value="{{ $z->id }}" @if($zone==$z->id) selected @endif>{{$z->name}}</option> @endforeach
                                </optgroup> 
                            @endforeach
                            </optgroup> 
                        @endforeach
                        </select>

				        <label>Year</label> 
                        <select name="year" id="year" onchange="updateTable()">
                            @foreach($years as $y)
                            <option value="{{ $y }}" @if($year==$y) selected @endif>{{ $y }}</option>
                            @endforeach
                        </select>
 				    </div>
                </div>
            
                @include('targets.actuals.actualtable', array('title'=>'Child Indicators',    'data'=>$data['Child Health'],    'year'=>$year))
                @include('targets.actuals.actualtable', array('title'=>'Maternal Indicators', 'data'=>$data['Maternal Health'], 'year'=>$year))
                @include('targets.actuals.actualtable', array('title'=>'Other Indicators',    'data'=>$data['Others'],          'year'=>$year))
        </div>
    </div>
</section>  
@stop


@section('script')
<script type="text/javascript">

    function isNumeric(n) { 
      return !isNaN(parseFloat(n)) && isFinite(n); 
    }

    function updateTable()
    {
       var zone = $('#location').val();
       var year = $('#year').val();
       window.location.href = "/cch/yabr3/targets/actuals/?year="+year+"&zone="+zone;
    }

    function updateActual(elem)
    {
       var oldval = $('#idc_oldval_'+elem).val();
       var newval = $('#idc_'+elem).val();
       // $('#idc_'+elem).attr('tooltip','');
       //$('#idc_'+elem).attr('tooltip-trigger','');

        if (oldval == newval) {
            $('#idc_'+elem).stop().animate({backgroundColor:'#ffffff'}, 1000);
        } else {
            if (isNumeric(newval) && newval!="")
            {
                $.ajax({
                  method: "POST",
                  url: "/cch/yabr3/targets/actuals/"+elem,
                  data: { actual: newval }
                }).done(function( msg ) {
                  if (!msg.error) {
                        $('#idc_oldval_'+elem).val(newval);
                        $('#idc_'+elem).css('background-color','#90ee90');
                        $('#idc_'+elem).stop().animate({backgroundColor:'#ffffff'}, 1000);
                  } else {
                    console.log(msg);
                    $('#idc_'+elem).css('background-color','#ff0000');
                    $('#idc_'+elem).focus();
                  }
                });
            } else {
                $('#idc_'+elem).css('background-color','#ff0000');
                //$('#idc_'+elem).attr('tooltip','Invalid entry. Please correct');
                //$('#idc_'+elem).attr('tooltip-trigger','focus');
                $('#idc_'+elem).focus();
            }
       }
    }

    $(function() {
       $('.factable').dataTable({
                    "bPaginate": true,
                    "iDisplayLength": 20,
                    "bLengthChange": false,
                    "bFilter": false,
                    "bSort": false,
                    "bInfo": true,
                    "bAutoWidth": false
        });
    });
</script>
@stop

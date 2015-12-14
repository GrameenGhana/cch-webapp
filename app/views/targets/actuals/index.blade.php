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
                    <div class="col-md-12">
				        <label>Zone</label> 
                        <select name="location" id="location" style="margin-right: 20px;">
                         @foreach($locations as $i => $district) 
                            <optgroup label="{{ $district->name }}">
                            @foreach($district->subdistricts as $k => $sd) 
                                <optgroup label="{{ $sd->name }}">
                                @foreach($sd->zones as $l => $z) <option value="{{ $z->id }}">{{$z->name}}</option> @endforeach
                                </optgroup> 
                            @endforeach
                            </optgroup> 
                        @endforeach
                        </select>

				        <label>Year</label> 
                        <select name="year" onchange="if (this.value) window.location.href=this.value">
                            @foreach($years as $year)
                            <option value="/cch/yabr3/targets/actual?year={{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
 				    </div>
                </div>
            
                <div class="row">
                    <div class="col-md-12">
                        <h2>Child Indicators</h2>
                        <table id="factable" style-"margin-top: 25px" class="factable table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Indictor</th>
                                    <th>Jan - {{ $year }}</th> <th>Feb - {{ $year }}</th> <th>Mar - {{ $year }}</th> <th>Apr - {{ $year }}</th>
                                    <th>May - {{ $year }}</th> <th>Jun - {{ $year }}</th> <th>Jul - {{ $year }}</th> <th>Aug - {{ $year }}</th>
                                    <th>Sep - {{ $year }}</th> <th>Oct - {{ $year }}</th> <th>Nov - {{ $year }}</th> <th>Dec - {{ $year }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($data['Child Health'] as $name => $idc)
                                <tr>
                                    <td>{{ $name }}</td>
                                    @for($i=0; $i<12; $i++)
                                    <td> {{ Form::text('idc_'.$idc[$i]['id'], $idc[$i]['actual'], 
                                                       array('class'=>'form-control','onblur'=>'updateActual('.$idc[$i]['id'].')', 'id'=>'idc_'.$idc[$i]['id']))  }}</td>

                                    @endfor
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>    

                <div class="row">
                    <div class="col-md-12">
                        <h2>Maternal Indicators</h2>
                        <table id="mfactable" style-"margin-top: 25px" class="factable table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Indictor</th>
                                    <th>Jan - {{ $year }}</th> <th>Feb - {{ $year }}</th> <th>Mar - {{ $year }}</th> <th>Apr - {{ $year }}</th>
                                    <th>May - {{ $year }}</th> <th>Jun - {{ $year }}</th> <th>Jul - {{ $year }}</th> <th>Aug - {{ $year }}</th>
                                    <th>Sep - {{ $year }}</th> <th>Oct - {{ $year }}</th> <th>Nov - {{ $year }}</th> <th>Dec - {{ $year }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($data['Maternal Health'] as $name => $idc)
                                <tr>
                                    <td>{{ $name }}</td>
                                    @for($i=0; $i<12; $i++)
                                    <td> {{ Form::text('idc_'.$idc[$i]['id'], $idc[$i]['actual'], 
                                                       array('class'=>'form-control','onblur'=>'updateActual('.$idc[$i]['id'].')', 'id'=>'idc_'.$idc[$i]['id']))  }}</td>
                                    @endfor
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>    

                <div class="row">
                    <div class="col-md-12">
                        <h2>Other Indicators</h2>
                        <table id="ofactable" style-"margin-top: 25px" class="factable table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Indictor</th>
                                    <th>Jan - {{ $year }}</th> <th>Feb - {{ $year }}</th> <th>Mar - {{ $year }}</th> <th>Apr - {{ $year }}</th>
                                    <th>May - {{ $year }}</th> <th>Jun - {{ $year }}</th> <th>Jul - {{ $year }}</th> <th>Aug - {{ $year }}</th>
                                    <th>Sep - {{ $year }}</th> <th>Oct - {{ $year }}</th> <th>Nov - {{ $year }}</th> <th>Dec - {{ $year }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($data['Others'] as $name => $idc)
                                <tr>
                                    <td>{{ $name }}</td>
                                    @for($i=0; $i<12; $i++)
                                    <td> {{ Form::text('idc_'.$idc[$i]['id'], $idc[$i]['actual'], 
                                                       array('class'=>'form-control','onblur'=>'updateActual('.$idc[$i]['id'].')', 'id'=>'idc_'.$idc[$i]['id']))  }}</td>
                                    @endfor
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>    
        </div>
    </div>
</section>  
@stop


@section('script')
<script type="text/javascript">
    function updateActual(elem)
    {
       var newval = $('#idc_'+elem).val();
       console.log(elem+': '+newval);
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

@extends('layouts.master')

@section('head')
   @parent
   {{ HTML::style('css/datatables/dataTables.bootstrap.css'); }} 
   {{ HTML::style('css/fullcalendar/fullcalendar.css'); }} 
   {{ HTML::style('css/fullcalendar/fullcalendar.print.css'); }} 
@stop

@section('content-header')
	    
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1> <i class="fa fa-hospital-o"></i> Facilities <small>Control panel</small> </h1>
    <ol class="breadcrumb">
         <li><a href="{{ URL::to('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
         <li>
     		<a href="{{ URL::to('facilities') }}"><i class="fa fa-hospital-o"></i> Facilities</a>
	</li>
        <li class="active">Calendar</li>
     </ol>
</section>
@stop

@section('content')

<section class="content invoice">
	<div class="row">
		<div class="col-xs-12">
        		<h2 class="page-header">{{ $district }} Calendar</h2>
      	</div>
	</div>

	<div class="row">
		<div class="col-xs-12">
            <b>Legend:</b>&nbsp;
            @foreach($legend as $fac => $color)
               &nbsp;<span href ="{{URL::to('facilities/calendar/'.$fac->id)}}" style="color: {{ $color }};">{{ $fac }}</span>
            @endforeach
            <br/><br/>
      	</div>
	</div>

	<div class="row">
        	<div class="col-md-12">
    	  		<div class="box box-primary">
					<div class="box-body no-padding">
						<div id="calendar"></div>
                	</div>
           		</div>
			</div>
	</div>
</section>	

@stop

@section('script')
{{ HTML::script('js/plugins/fullcalendar/fullcalendar.min.js'); }}

<script type="text/javascript">
$('#calendar').fullCalendar({
	header: {
   		left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay'
    },
    buttonText: {
        prev: "<span class='fa fa-caret-left'></span>",
        next: "<span class='fa fa-caret-right'></span>",
        today: 'today',
        month: 'month',
        week: 'week',
        day: 'day'
   },

   events: [
    @foreach($events as $ev){
        {{ '       title: \''.$ev['title'].'\',' }} 
//        {{ '       title: \''.$ev['title'].'\',' }} 
        {{ '       start: '.$ev['start'].',' }} 
        {{ '       end: '.$ev['end'].',' }} 
        {{ '       backgroundColor: "'.$ev['backgroundColor'].'",' }} 
        {{ '       borderColor: "'.$ev['borderColor'].'"' }} 
        {{ '    },' }} 
    @endforeach
   ],
   editable: true,
   droppable: false,
});
</script>

@stop

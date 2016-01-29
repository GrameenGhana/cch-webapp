@extends('layouts.master')

@section('head')
@parent
{{ HTML::style('css/datatables/dataTables.bootstrap.css'); }} 
@stop

@section('content-header')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1> <i class="fa fa-hospital-o"></i> Facility Types <small>Control panel</small> </h1>
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ URL::to('facilitytypes') }}"><i class="fa fa-hospital-o"></i> Facility Types</a></li>
        <li class="active">Edit</li>
    </ol>
</section>
@stop

@section('content')

<section class="content invoice">
    <!-- title row -->
    <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">Create a Facility Type</h2>                            
        </div><!-- /.col -->
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                {{ Form::open(array('url'=> 'facilitytypes','method'=>'post')) }}

                <div class="box-body">
                    @if(Session::has('flash_error'))
                    <div class="callout callout-danger">
                        <h4>Error!</h4> <br/>
                        {{ HTML::ul($errors->all()) }}
                    </div>
                    @endif
                    <div class="form-group">
                        {{ Form::label('name','Name') }}
                        {{ Form::text('name',Input::old('name'),array('class'=>'form-control','placeholder'=>'Enter Name','id'=>'nm')) }}

                    </div>
                    <div class="form-group">
                        {{ Form::label('code','Code') }}
                        {{ Form::text('code',Input::old('code'),array('class'=>'form-control','placeholder'=>'Enter Code','id'=>'cd')) }}
                    </div>

                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    {{ Form::submit('Add Facility Type',array('class'=>'btn btn-primary')) }}
                </div>

                {{ Form::close() }}
            </div><!-- /.box -->
        </div>
    </div>
</section>	
@stop


@section('script')
<script type="text/javascript">
    $(function() {
        //              $('#group').change(function() { showhidefacs(); });                
        $('#nm').keyup(function() {
            $("#cd").val($("#nm").val().replace(/[^a-z0-9\s]/gi, '_').replace(/[_\s]/g, '_').toUpperCase())
        });
    });
</script>
@stop

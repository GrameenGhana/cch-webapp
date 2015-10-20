@extends('layouts.master')

@section('head')
@parent
{{ HTML::style('css/datatables/dataTables.bootstrap.css'); }} 
@stop

@section('content-header')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1> <i class="fa fa-users"></i> Users <small>Control panel</small> </h1>
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ URL::to('users') }}"><i class="fa fa-users"></i> Users</a></li>
        <li class="active">Create</li>
    </ol>
</section>
@stop

@section('content')

<section class="content invoice">
    <!-- title row -->
    <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">Add a User</h2> 
        </div><!-- /.col -->
    </div>

    @if(Session::has('flash_error'))
    <div class="row">
        <div class="col-xs-12">
            <div class="callout callout-danger">
                <h4>Error!</h4> <br/>
                {{ HTML::ul($errors->all()) }}
            </div>
        </div>
    </div>
    @endif

    {{ Form::open(array('url'=> 'users','method'=>'post')) }}
    <div class="row">
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-body">
                    <fieldset>
                        <legend>Login info </legend>	
                        <div class="form-group">
                            {{ Form::label('username','Username') }}
                            {{ Form::text('username',Input::old('username'),array('class'=>'form-control','placeholder'=>'Enter username')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('password','Password') }}
                            <input name="password" type="password" value="" class="form-control" id="password">      
                        </div>
                        <div class="form-group">
                            {{ Form::label('confirmpassword','Confirm Password') }}
                            <input name="confirmpassword" type="password" value="" class="form-control" id="confirmpassword">
                        </div>

                    </fieldset>
                </div>
            </div>
<div class="box box-primary">
                <div class="box-body">
                    <fieldset>
                        <legend>User Comment</legend>    
                        <div class="form-group">
                            {{ Form::text('comment',Input::old('comment'),array('class'=>'form-control','placeholder'=>'Enter Comment')) }}
                        </div>

                       
                    </fieldset>
                </div>
            </div>

        </div>

        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-body">

                    <fieldset><legend>Work info </legend>	
                        <div class="form-group">
                            {{ Form::label('group','Group') }}
                            {{ Form::select('group', $groups, Input::old('group'),array('class'=>'form-control','placeholder'=>'Enter group')) }}
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-6">
                                    {{ Form::label('role','Role') }}
                                    {{ Form::select('role', $roles, Input::old('role'),array('class'=>'form-control','placeholder'=>'Enter role')) }}
                                </div>
                                <div class="col-xs-6">
                                    {{ Form::label('ischn','Is CHN?') }}
                                    {{ Form::select('ischn', array('0'=>'No','1'=>'Yes'), Input::old('ischn'),array('class'=>'form-control','placeholder'=>'Enter CHN status')) }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('title','Title') }}
                            {{ Form::text('title',Input::old('title'),array('class'=>'form-control','placeholder'=>'Enter title')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('primary_facility','My Facility') }}
                            {{ Form::select('primary_facility', $pfacilities, Input::old('primary_facility'),array('class'=>'form-control','placeholder'=>'Enter Primary Facility')) }}
                        </div>


   <div class="form-group">
                            {{ Form::label('zone','My Zone') }}
                            {{ Form::select('zone', $zones, Input::old('zone'),array('class'=>'form-control','placeholder'=>'Enter  Zone')) }}
                        </div>
                    </fieldset>

                </div><!-- /.box-body -->
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-body">

                    <fieldset><legend>Personal info </legend>	
                        <div class="form-group">
                            {{ Form::label('first_name','First Name') }}
                            {{ Form::text('first_name',Input::old('first_name'),array('class'=>'form-control','placeholder'=>'Enter first name')) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('last_name','Last Name') }}
                            {{ Form::text('last_name',Input::old('last_name'),array('class'=>'form-control','placeholder'=>'Enter last name')) }}
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    {{ Form::label('phone_number','Phone Number') }}
                                    {{ Form::text('phone_number',Input::old('phone_number'),array('class'=>'form-control','placeholder'=>'Enter phone number')) }}
                                </div>
                                <div class="col-md-6">
                                    {{ Form::label('device_id','Device') }}
                                    {{ Form::select('device_id', $devices, Input::old('device_id'),array('class'=>'form-control','placeholder'=>'Enter Device')) }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                              <div class="row">
                                <div class="col-md-6">
                            {{ Form::label('gender','Gender') }}
                            {{ Form::select('gender', array('unspecified'=>'Unknown','female'=>'Female','male'=>'Male'), Input::old('gender'),array('class'=>'form-control','placeholder'=>'Enter Gender')) }}
                                </div>  <div class="col-md-6">
                                       {{ Form::label('status','Status') }}
                            {{ Form::select('status', array('ACTIVE'=>'Active','TEST'=>'Test','ERROR'=>'Error','INACTIVE'=>'In Active','OWNDEVICE'=>'OWN DEVICE'), Input::old('status'),array('class'=>'form-control','placeholder'=>'Select Status')) }}
                        
                                </div> </div>
                    </fieldset>

                </div>
            </div>
        </div>
    </div>

    <div class="row" id="superfac">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                    <fieldset><legend>{{ (Request::is('districtadmin*')) ? 'Administered Facilities' : 'Supervised Facilities' }}
</legend>	

                        <div class="row">
                            @foreach ($facilities as $d => $facs)
                            @if ($d != 'La Dade Kotopon')
                            <?php ?>
                            {{-- */$dId = strtolower(str_replace(" ", "_", $d))/* --}}
                            <div class="col-xs-3" id="{{$dId}}">
                               <b class="parent btn btn-primary"><input type="checkbox" value="{{$dId}}" name="ploc{{$dId}}" class="ploc" /><strong>{{$d}}</strong>
                                </b><br/>
                                <br/>

                                @foreach($facs as $f)
                                <div class="form-group">
                                    {{ Form::checkbox('locations[]', $f->id, in_array($f->id, (array) Input::old('locations'))) }}&nbsp;{{{ $f->name }}} 
                                </div>
                                @endforeach

                            </div>
                            @endif
                            @endforeach
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-xs-3">
                                <b>La Dade Kotopon</b><br/><br/>
                                <div class="form-group">
                                    {{ Form::checkbox('locations[]', 144, in_array(144, (array) Input::old('locations'))) }}&nbsp;CCH Project
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-6">
            <div class="box-footer">
                {{ Form::submit('Create User',array('class'=>'btn btn-primary')) }}
            </div>
        </div>
    </div>
    {{ Form::close() }}

</section>	
@stop

@section('script')
<script type="text/javascript">
    $(function() {
        //              $('#group').change(function() { showhidefacs(); });                
        $('#role').change(function() {
            showhidefacs();
        });

        $('.parent input:checkbox').on('ifChecked ifUnchecked', function(e) {
         
var chk="uncheck";

 if (e.type == 'ifChecked') {
          chk ='check';
        } 

            $("#"+$(this).val()).find('input').each(function() {
                $(this).iCheck(chk);
            })
        });

    });



    function showhidefacs()
    {
//                  var show = ($('#group').val()=='CCH') ? true : false;
        var show = (/.*?Supervisor.*?/.test($('#role').val())) ||(/.*?District Admin.*?/.test($('#role').val())) ? true : show;

        if (show) {
            $('#superfac').show();
        } else {
            $('#superfac').hide();
        }
    }
    showhidefacs();
</script>
@stop


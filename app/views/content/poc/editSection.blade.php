@extends ('layouts.master')

@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> <i class="fa fa-hospital-o"></i> POC CMS</h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">POC CMS</li>
        </ol>
    </section>
@stop

@section('content')
<section class="content">

<div class="col-sm-12">
<div class="row">
    <div class="col-lg-6">
        @if ($errors->any())
            <ul>
            {{ implode('', $errors->all('<div class="alert alert-danger fade in"> <a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> :message</div>')) }}
            </ul>
        @endif
        @if (Session::has('message'))
            <div class="alert alert-success fade in"> <a href="#" class="close" data-dismiss="alert">&times;</a><strong>Success!</strong> {{ Session::get('message') }}</div>
        @endif
        <form role="form" method="post" action="editsectionvalue">
        <input type="hidden" name="id" id="id" value="{{$section_values->id}}">
            <div class="form-group">
                <label>Name of Section</label>
                <input class="form-control" name="name_of_section" id="name_of_section" value="{{$section_values->name_of_section}}">
            </div>
             <div class="form-group">
                <label>Sub Section</label>
                  {{ Form::select('sub_section',[null=>'Select']+['ANC Diagnostic'=>'ANC Diagnostic','ANC Counselling'=>'ANC Counselling','PNC Diagnostic'=>'PNC Diagnostic','PNC Counselling'=>'PNC Counselling','CWC Diagnostic'=>'CWC Diagnostic','CWC Counselling'=>'CWC Counselling','CWC References'=>'CWC References','CWC Calculators'=>'CWC Calculators'],$section_values->sub_section,array('class'=>'form-control','id' => 'sub_section'))}}   
            

            </div>
            <div class="form-group">
                <label>Shortname</label>
                <input class="form-control" placeholder="shortname" name="shortname" id="shortname" value="{{$section_values->shortname}}">
                 <span class="btn-warning btn-circle" style="cursor:pointer" id="generate">Generate Shortname</span>
            </div>
            
            <button type="submit" class="btn btn-success">Edit section</button>
            <button type="reset" class="btn btn-danger">Reset</button>
        </form>
    </div>
    
</div>
</div>
</section>
@stop

@section('script')
<script type="text/javascript">
    $(document).ready(function() {
    $("#generate").click(function () {
    var lower = $('input#name_of_section').val().toLowerCase(); // to lower case
    var hyp = lower.replace(/ /g,"_");         
    $("#shortname").val(hyp);

});
    
    });
</script>
@stop

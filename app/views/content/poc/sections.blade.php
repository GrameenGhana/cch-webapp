@extends ('layouts.master')

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
        <h1> <i class="fa fa-hospital-o"></i> Sections</h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">POCCMS - Sections</li>
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
        <form role="form" method="post" action="addsection">
            <div class="form-group">
                <label>Name of Section</label>
                <input class="form-control" name="name_of_section" id="name_of_section">
            </div>
             <div class="form-group">
                <label>Sub Section</label>
                <select class="form-control" id="sub_section" name="sub_section">
                <option>Select</option>
                    <option>ANC Diagnostic</option>
                    <option>ANC Counselling</option>
                    <option>ANC References</option>
                    <option>PNC Diagnostic Newborn</option>
                    <option>PNC Diagnostic Mother</option>
                    <option>PNC Counselling</option>
                    <option>PNC References</option>
                    <option>CWC Diagnostic</option>
                    <option>CWC Counselling</option>
                    <option>CWC References</option>
                    <option>CWC Calculators</option>
                </select>
               

            </div>
             <div class="form-group">
                <label>Section Description</label>
                <input class="form-control" name="section_desc" id="section_desc">
                <p class="help-block" style="color:Red">More descriptive and unique name of a section eg: CWC Anaemia</p>
            </div>
            
            <div class="form-group">
                <label>Shortname</label>
                <input class="form-control" placeholder="Enter text" name="shortname" id="shortname">
                 <span class="btn-warning btn-circle" style="cursor:pointer" id="generate">Generate Shortname</span>
            </div>
            
            <button type="submit" class="btn btn-success">Create section</button>
            <button type="reset" class="btn btn-danger">Reset</button>
        </form>
    </div>

    </div>
    
</div>
</section>
@stop

@section('script');
<script type="text/javascript">
    $(document).ready(function() {
    $("#generate").click(function () {
    var lower = $.trim($('input#section_desc').val().toLowerCase()); // to lower case
    var value=lower.replace (/[`~!@#$%^&*()|+\-=?;:'",.<>\{\}\[\]\\\/]/g,"");
    var hyp = value.replace(/ /g,"_");        
    
    $("#shortname").val(hyp);
});
    });
</script>
@stop

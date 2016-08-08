@extends ('layouts.master')



@section('content')

<section class="content">

<div class="row">
{{ Form::open(array('url'=> 'content/poccms/postreference','method'=>'post','files'=>true)) }}
<div class="col-md-6">
   @if ($errors->any())
            <ul>
            {{ implode('', $errors->all('<div class="alert alert-danger fade in"> <a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> :message</div>')) }}
            </ul>
        @endif
        @if (Session::has('message'))
            <div class="alert alert-success fade in"> <a href="#" class="close" data-dismiss="alert">&times;</a><strong>Success!</strong> {{ Session::get('message') }}</div>
        @endif
        <div class="box box-primary">
            <div class="box-body">
                <fieldset><legend>Learning Center References</legend>   
                  <div class="form-group">
                <label>Reference Name</label>
                <input class="form-control" name="reference_desc" id="reference_desc">
                <p class="help-block">Required**</p>
            </div>
             <div class="form-group">
                <label>Upload File</label>
                <input class="form-control" name="file" id="file" type="file">
                <p class="help-block"><span style="color: red">File must be less than 20MB</span>  Must be in PDF format'**</p>
            </div>
             <div class="form-group">
                <label>Page Shortname</label>
                <input class="form-control" name="shortname" id="shortname">
                <span class="btn-warning btn-circle" style="cursor:pointer" id="generate">Generate Shortname</span>
            </div>
            <!-- Modals-->
            <button type="submit" class="btn btn-success">Create Reference</button>
            <button type="reset" class="btn btn-danger">Reset</button>  
                </fieldset>
            </div>
        </div>

          <div class="box box-primary">
            <div class="box-body">
            <legend>Uploaded References</legend>   
                <table class="table table-striped table-bordered"  id="dataTables-example">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Size</th>
                </tr>
            </thead>
            <tbody>
              @foreach($references as $r)
                <tr>
                    <td>{{$r->reference_desc}}</td>
                    <td>{{$r->size}}</td>
                </tr>
                 @endforeach
                
            </tbody>
        </table>    
            </div>
        </div>
    </div>
    </section>
@stop

@section('script');


<script type="text/javascript">
    $(document).ready(function() {
    $("#generate").click(function () {
    var lower = $.trim($('input#reference_desc').val().toLowerCase()); // to lower case
    var hyp = lower.replace(/ /g,"_");  
    var value=hyp.replace (/[`~!@#$%^&*()|+\-=?;:'",.<>\{\}\[\]\\\/]/g,"");       
    $("#shortname").val(value);

});
    });
</script>

@stop

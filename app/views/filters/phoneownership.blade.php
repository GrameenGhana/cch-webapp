<div class="form-group">
    <label class="font-normal" for="{{ $prefix }}_location">Phone Ownership:</label>
    <div class="input-group">
        {{ Form::select($prefix.'_phoneownership', $dashboard->phoneownershipForSelect(), 'all', 
                        array('data-placeholder'=>'Choose phone ownership...',
                              'id'=>$prefix.'_phoneownership',
                              'class'=>$prefix.'_po_chosen-select',
                              'multiple',
                              'style'=>'width: 300px')) }} 
    </div>
</div>


<script type="text/javascript">
    var {{ $prefix }}_po_config = {
               '.{{ $prefix }}_po_chosen-select'           : {},
               '.{{ $prefix }}_po_chosen-select-deselect'  : {allow_single_deselect:true},
               '.{{ $prefix }}_po_chosen-select-no-single' : {disable_search_threshold:10},
               '.{{ $prefix }}_po_chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
               '.{{ $prefix }}_po_chosen-select-width'     : {width:"95%"}
   }

   for (var selector in {{ $prefix }}_po_config) {
        $(selector).chosen({{ $prefix }}_po_config[selector]);
   }

   $('#{{ $prefix }}_phoneownership').change(function(e) { {{ $prefix }}_callback(); });

   // Register elements so on update their values can be obtained
   {{ $prefix }}_events_register.add({name:'phoneownership',val:'#{{ $prefix }}_phoneownership'});
</script>
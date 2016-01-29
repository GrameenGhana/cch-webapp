<div class="form-group">
    <label class="font-normal" for="{{ $prefix }}_parity">Number of previous children:</label>
    <div class="input-group">
        <select data-placeholder="Choose a parity..." 
                id="{{ $prefix}}_parity"
                name="{{ $prefix}}_parity"
                class="{{ $prefix}}_pa_chosen-select" style="width:300px;">
          <option value="all" selected>All</option>
          <option value="0">No previous children</option>
          <option value="1">One previous child</option>
          <option value="2">More than one child</option>
        </select>
    </div>
</div>

<script type="text/javascript">
    var {{ $prefix }}_pa_config = {
               '.{{ $prefix }}_pa_chosen-select'           : {},
               '.{{ $prefix }}_pa_chosen-select-deselect'  : {allow_single_deselect:true},
               '.{{ $prefix }}_pa_chosen-select-no-single' : {disable_search_threshold:10},
               '.{{ $prefix }}_pa_chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
               '.{{ $prefix }}_pa_chosen-select-width'     : {width:"95%"}
   }

   for (var selector in {{ $prefix }}_pa_config) {
        $(selector).chosen({{ $prefix }}_pa_config[selector]);
   }

   $('#{{ $prefix }}_parity').change(function(e) { {{ $prefix }}_callback(); });

   // Register elements so on update their values can be obtained
   {{ $prefix }}_events_register.add({name:'parity',val:'#{{ $prefix }}_parity'});
</script>
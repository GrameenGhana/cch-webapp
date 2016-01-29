<div class="form-group">
    <label class="font-normal" for="{{ $prefix }}_enrollmentage">Age at MM enrollment:</label>
    <div class="input-group">
        <select data-placeholder="Choose a age..." 
                id="{{ $prefix}}_enrollmentage"
                name="{{ $prefix}}_enrollmentage"
                class="{{ $prefix}}_enrollmentage_chosen-select" multiple style="width:300px;">
          <option value="all" selected>All</option>
          <option value="0">15 - 24 years</option>
          <option value="1">25 - 50 years</option>
          <option value="2">More than 50 years</option>
        </select>
    </div>
</div>

<script type="text/javascript">
    var {{ $prefix }}_enrollmentage_config = {
               '.{{ $prefix }}_enrollmentage_chosen-select'           : {},
               '.{{ $prefix }}_enrollmentage_chosen-select-deselect'  : {allow_single_deselect:true},
               '.{{ $prefix }}_enrollmentage_chosen-select-no-single' : {disable_search_threshold:10},
               '.{{ $prefix }}_enrollmentage_chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
               '.{{ $prefix }}_enrollmentage_chosen-select-width'     : {width:"95%"}
   }

   for (var selector in {{ $prefix }}_enrollmentage_config) {
        $(selector).chosen({{ $prefix }}_enrollmentage_config[selector]);
   }

   $('#{{ $prefix }}_enrollmentage').change(function(e) { {{ $prefix }}_callback(); });

   // Register elements so on update their values can be obtained
   {{ $prefix }}_events_register.add({name:'enrollmentage',val:'#{{ $prefix }}_enrollmentage'});
</script>

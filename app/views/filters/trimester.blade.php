<div class="form-group">
    <label class="font-normal" for="{{ $prefix }}_trimester">Trimester at MM enrollment:</label>
    <div class="input-group">
        <select data-placeholder="Choose a trimester..." 
                id="{{ $prefix}}_trimester"
                name="{{ $prefix}}_trimester"
                class="{{ $prefix}}_trim_chosen-select" multiple style="width:300px;">
          <option value="all" selected>Ignore</option>
          <option value="first">First trimester</option>
          <option value="second">Second trimester</option>
          <option value="third">Third trimester</option>
        </select>
    </div>
</div>

<script type="text/javascript">
    var {{ $prefix }}_trim_config = {
               '.{{ $prefix }}_trim_chosen-select'           : {},
               '.{{ $prefix }}_trim_chosen-select-deselect'  : {allow_single_deselect:true},
               '.{{ $prefix }}_trim_chosen-select-no-single' : {disable_search_threshold:10},
               '.{{ $prefix }}_trim_chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
               '.{{ $prefix }}_trim_chosen-select-width'     : {width:"95%"}
   }

   for (var selector in {{ $prefix }}_trim_config) {
        $(selector).chosen({{ $prefix }}_trim_config[selector]);
   }

   $('#{{ $prefix }}_trimester').change(function(e) { {{ $prefix }}_callback(); });

   // Register elements so on update their values can be obtained
   {{ $prefix }}_events_register.add({name:'trimester',val:'#{{ $prefix }}_trimester'});
</script>
<div class="form-group">
    <label class="font-normal" for="{{ $prefix }}_clienttype">Client Type:</label>
    <div class="input-group">
        <select data-placeholder="Choose a client type..." 
                id="{{ $prefix}}_clienttype"
                name="{{ $prefix}}_clienttype"
                class="{{ $prefix}}_clienttype_chosen-select" multiple style="width:300px;">
          <option value="all" selected>All</option>
          <option value="Pregnant Woman">Pregnant women</option>
          <option value="New Mother">New mothers</option>
          <option value="Infant">Infants</option>
          <option value="Child">Children (> 1 year)</option>
          <option value="Young">Young</option>
          <option value="Reproductive Age">Non-pregnant women in Reproductive Age (15-49 years)</option>
          <option value="Men">Men</option>
          <option value="Older">Older women (> 49 years)</option>
        </select>
    </div>
</div>

<script type="text/javascript">
    var {{ $prefix }}_clienttype_config = {
               '.{{ $prefix }}_clienttype_chosen-select'           : {},
               '.{{ $prefix }}_clienttype_chosen-select-deselect'  : {allow_single_deselect:true},
               '.{{ $prefix }}_clienttype_chosen-select-no-single' : {disable_search_threshold:10},
               '.{{ $prefix }}_clienttype_chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
               '.{{ $prefix }}_clienttype_chosen-select-width'     : {width:"95%"}
   }

   for (var selector in {{ $prefix }}_clienttype_config) {
        $(selector).chosen({{ $prefix }}_clienttype_config[selector]);
   }

   $('#{{ $prefix }}_clienttype').change(function(e) { {{ $prefix }}_callback(); });

   // Register elements so on update their values can be obtained
   {{ $prefix }}_events_register.add({name:'clienttype',val:'#{{ $prefix }}_clienttype'});
</script>
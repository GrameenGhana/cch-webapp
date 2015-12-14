<div class="form-group" id="{{ $prefix }}_datepicker">
    <label class="font-normal" for="{{ $prefix }}_datepicker">Dates:</label>
    <div class="input-daterange input-group">
        <input type="text" class="input-sm form-control" id="{{ $prefix }}_start" name="{{ $prefix }}_start" value="{{ date('01/01/Y') }}"/>
        <span class="input-group-addon">to</span>
        <input type="text" class="input-sm form-control" id="{{ $prefix }}_end" name="{{ $prefix }}_end" value="{{ date('m/d/Y') }}" />
    </div>
</div>

<div class="form-group">
    <label class="font-normal" for="{{ $prefix }}_location">Zone:</label>
    <div class="input-group">
        {{ Form::select($prefix.'_location', $dashboard->locationZonesForSelect(), 'all', 
                        array('data-placeholder'=>'Choose a location...',
                              'id'=>$prefix.'_location',
                              'class'=>$prefix.'_chosen-select',
                              'multiple',
                              'style'=>'width: 300px',
                              'tabindex'=>'1')) }} 
    </div>
</div>


<script type="text/javascript">
    $('#{{ $prefix }}_datepicker .input-daterange').datepicker({
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true
    });

    var {{ $prefix }}_config = {
               '.{{ $prefix }}_chosen-select'           : {},
               '.{{ $prefix }}_chosen-select-deselect'  : {allow_single_deselect:true},
               '.{{ $prefix }}_chosen-select-no-single' : {disable_search_threshold:10},
               '.{{ $prefix }}_chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
               '.{{ $prefix }}_chosen-select-width'     : {width:"95%"}
   }

   for (var selector in {{ $prefix }}_config) {
        $(selector).chosen({{ $prefix }}_config[selector]);
   }

   $('#{{ $prefix }}_end').change(function(e)      { {{ $prefix }}_callback(); });
   $('#{{ $prefix }}_start').change(function(e)    { {{ $prefix }}_callback(); });
   $('#{{ $prefix }}_location').change(function(e) { {{ $prefix }}_callback(); });

   // Register elements so on update their values can be obtained
   {{ $prefix }}_events_register.add({name:'location',val:'#{{ $prefix }}_location'});
   {{ $prefix }}_events_register.add({name:'startdate',val:'#{{ $prefix }}_start'});
   {{ $prefix }}_events_register.add({name:'enddate',val:'#{{ $prefix }}_end'});
</script>

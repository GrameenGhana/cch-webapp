<script type="text/javascript">var qarviu_events_register = new EventTracker();</script>

<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h2 style="display:inline">Version In Use by Active CHNs</h2>
                </div>

                <div class="ibox-content" style="height: 550px;">
                    <div class="row">
                        <div class="col-lg-8">
                                <table id="qarviu_table" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>District</th>
                                            @foreach($dashboard->VersionByPeriod() as $v)
                                                <th>{{ $v }}</th>
                                            @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                   </table>
                        </div>

                        <div class="col-lg-4">
                            <ul class="stat-list">
                                <li>
                                    @include('filters.qar-dateloc', array('prefix'=>'qarviu')) 
                                </li>
                            </ul>
                        </div>
                    </div>
                 </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var table;

    $(function() { 
        table = $('#qarviu_table').DataTable({
          "bPaginate": true,
          "bLengthChange": true,
          "bFilter": false,
          "bSort": true,
          "bInfo": true,
          "bAutoWidth": false,
          "iDisplayLength": 10,
          "ajax": qarviu_geturl(), 
          "columns": [
            { "data": "District" },
            @foreach($dashboard->VersionByPeriod() as $v)
            { "data": "{{ $v }}"  },
            @endforeach
          ]
        });
    });

    function qarviu_geturl()
    {
       var params = qarviu_events_register.getParams();
       var url = base_url + "/api/v1/dashboard/qar/viu?"+jQuery.param(params);
       return url;
    }

    function qarviu_callback() 
    {
        table.ajax.url(qarviu_geturl()).load();
        return true;
    }
</script>

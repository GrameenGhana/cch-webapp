<script type="text/javascript">var qaruss_events_register = new EventTracker();</script>

<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h2 style="display:inline">User Status Summary</h2>
                </div>

                <div class="ibox-content" style="height: 550px;">
                    <div class="row">
                        <div class="col-lg-8">
                                <table id="qaruss_table" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>District</th>
                                                <th>Active Users</th>
                                                <th>Error</th>
                                                <th>Inactive</th>
                                                <th>Own Device</th>
                                                <th>Test</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                   </table>
                        </div>

                        <div class="col-lg-4">
                            <ul class="stat-list">
                                <li>
                                    @include('filters.qar-dateloc', array('prefix'=>'qaruss')) 
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
        table = $('#qaruss_table').DataTable({
          "bPaginate": true,
          "bLengthChange": true,
          "bFilter": false,
          "bSort": true,
          "bInfo": true,
          "bAutoWidth": false,
          "iDisplayLength": 10,
          "ajax": geturl(), 
          "columns": [
            { "data": "District" },
            { "data": "ACTIVE" },
            { "data": "ERROR" },
            { "data": "INACTIVE" },
            { "data": "OWNDEVICE" },
            { "data": "TEST" }
          ]
        });
    });

    function geturl()
    {
       var params = qaruss_events_register.getParams();
       var url = base_url + "/api/v1/dashboard/qar/uss?"+jQuery.param(params);
       return url;
    }

    function qaruss_callback() 
    {
        table.ajax.url(geturl()).load();
        return true;
    }
</script>

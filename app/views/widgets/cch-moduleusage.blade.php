<script type="text/javascript">var moduleusage_events_register = new EventTracker();</script>

<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>CCH: Module Usage</h5>
                    <div class="ibox-tools">
                                <!--
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a>
                                -->
                    </div>
                </div>

                <div class="ibox-content" style="height: 350px;">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="flot-chart">
                                <div style="margin-top: 30px;height: 300px; width: 100%;" id="moduleusage-chart"></div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <ul class="stat-list">
                                <li>
                                    @include('filters.regiondate', array('prefix'=>'moduleusage')) 
                                </li>
                                <li>
                                </li>
                                <li>
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
    var moduleusage_chart = new Highcharts.Chart({
        chart: {renderTo: 'moduleusage-chart',  type: 'column' },
        title:{ text: ""},
        subtitle: {
               text: 'Click the columns to view page break down.'
       },
        xAxis: {
            type: 'category',
            title: {
                text: "Modules"
            },
        },
        yAxis: {
            title: {
                text: 'Time Spent (hours)',
                align: 'high',
            },
        },
        legend: {
            enabled: false
        },
        plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y:.1f} hours'
                    }
                }
        },
        tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b> hours<br/>'
                },
        credits: { enabled: false },
        series: [
            {
                name: "Modules",
                colorByPoint: true,
                data: [
                    @foreach($dashboard->moduleUsage() as $module => $v)
                     { name:  '{{ $module }}', y: {{ $v }}, drilldown: '{{ $module }}' },
                    @endforeach 
                ]
    
            }
        ],
       drilldown: {
            series: [ 
                    @foreach($dashboard->moduleUsageBySection() as $module => $v)
                     { 
                        name:  '{{ $module }}', 
                        id: '{{ $module }}',
                        data: [
                            @foreach($v as $i)
                            ['{{ $i[0] }}',{{ $i[1] }}],
                            @endforeach
                        ]
                     },
                    @endforeach 
                   ]
        }
    });
            

    function moduleusage_callback() 
    {
        var params = moduleusage_events_register.getParams();

        $.ajax({
                type: "POST",
                url : base_url + "/moduleusagebytype",
                data : params,
                success : function(payload) {
                        console.log(payload);
                        /*data = [];
                        for(k in payload["info"]) {
                            var v = {  
                                       y: payload["info"][k], 
                                       legendText: k+": "+payload["info"][k]+"%",
                                       indexLabel: k+": "+payload["info"][k]+"%"
                                    }
                            data.push(v);
                        }
                        moduleusage_chart.options["data"][0]["dataPoints"]= data;
                        moduleusage_chart.render();
                        */
                }
        });
        return true;
    }

</script>

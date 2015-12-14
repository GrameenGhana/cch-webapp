<script type="text/javascript">var idcoh_events_register = new EventTracker();</script>

<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Indicators: Maternal Health</h5>
                    <div class="ibox-tools"> </div>
                </div>

                <div class="ibox-content" style="height: 350px;">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="flot-chart">
                                <div style="margin-top: 30px;height: 300px; width: 100%;" id="idcoh-chart"></div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <ul class="stat-list">
                                <li>
                                    @include('filters.regiondatezones', array('prefix'=>'idcoh')) 
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
    var idcoh_chart = new Highcharts.Chart({
        chart: {renderTo: 'idcoh-chart',  type: 'column', 

        events: {
                drilldown: function (e) {
                    if (!e.seriesOptions) {

                        var chart = this;
                        var params = idcoh_events_register.getParams();
                        params['type'] = 'Others';
                        
                        chart.showLoading('Getting data ...');
                        
                        $.ajax({
                            type: "POST",
                            url : base_url + "/indicatorsdatabycare",
                            data : params,
                            success : function(payload) {
                                if (!payload.error)
                                {
                                    data = [];
                                    for(k in payload["info"]) {
                                        var v = { 
                                            id: k,
                                            name: k , 
                                            data: []
                                        };

                                        for(j in payload["info"][k])
                                        {
                                            v.data.push([payload["info"][k][j][0], parseFloat(payload["info"][k][j][1])]);
                                        }
                                        data[k] = v;
                                    }
                                    chart.hideLoading();
                                    chart.addSeriesAsDrilldown(e.point, data[e.point.name]);
                                }
                            }
                        });
                    }
                }
            } 
        },
        title:{ text: ""},
        subtitle: {
               text: 'Click the columns to view page break down.'
       },
        xAxis: {
            type: 'category',
            title: {
                text: "Age Group"
            },
        },
        yAxis: {
            title: {
                text: '% Coverage'
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
                        format: '{point.y}%'
                    }
                }
        },
        tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}%</b> coverage<br/>'
                },
        credits: { enabled: false },
        series: [
            {
                name: "Age Group",
                colorByPoint: true,
                data: [
                    @foreach($dashboard->indicatorsData(array('type'=>'Others')) as $g => $v)
                     { name:  '{{ $g }}', y: {{ $v }}, drilldown: true },
                    @endforeach 
                ]
    
            }
        ],
       drilldown: { series: [] }
    });
            

    function idcoh_callback() 
    {
        var params = idcoh_events_register.getParams();
        params['type'] = 'Others';

        $.ajax({
                type: "POST",
                url : base_url + "/indicatorsdata",
                data : params,
                success : function(payload) {
                        if (!payload.error)
                        {
                            data = [];
                            for(k in payload["info"]) {
                                var v = {  
                                       id: k, 
                                       name: k, 
                                       drilldown: true,
                                       y: parseFloat(payload["info"][k]) 
                                    }
                                data.push(v);
                            }

                            // add new data
                            while(idcoh_chart.series.length > 0)
                                  idcoh_chart.series[0].remove(true);
                            idcoh_chart.addSeries( { data: data });
                        }
                }
        });
        return true;
    }

</script>

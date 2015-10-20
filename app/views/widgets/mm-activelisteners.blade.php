<script type="text/javascript">var mmacl_events_register = new EventTracker();</script>

<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Mobile Midwife Coverage: Clients who actively listen to their messages</h5>
                    <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                <!--
                                <a class="close-link">
                                    <i class="fa fa-times"></i>
                                </a>
                                -->
                    </div>
                </div>

                <div class="ibox-content" style="height: 450px;">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="flot-chart">
                                <div style="margin-top: 30px;height: 400px; width: 100%;" id="mmacl-chart"></div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <ul class="stat-list">
                                <li>
                                    @include('filters.regiondate', array('prefix'=>'mmacl')) 
                                </li>
                                <li>
                                    @include('filters.clienttype', array('prefix'=>'mmacl')) 
                                </li>
                                <li>
                                    @include('filters.phoneownership', array('prefix'=>'mmacl')) 
                                </li>
                                <li>
                                    @include('filters.parity', array('prefix'=>'mmacl')) 
                                </li>
                                <li>
                                    @include('filters.trimester', array('prefix'=>'mmacl')) 
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
    var mmacl_chart = new Highcharts.Chart({
        chart: {renderTo: 'mmacl-chart',  type: 'column' },

        title: {text: ''},

        xAxis: {
            categories: ['Personal', 'Household', 'Public'],
            title: { text: 'Phone Ownership'}
        },

        yAxis: {
            allowDecimals: false,
            min: 0,
            title: {
                text: '% of MM Clients'
            }
        },

        tooltip: {
            formatter: function () {
                return '<b>' + this.x + '</b><br/>' +
                    this.series.name + ': ' + this.y + '%<br/>';
            }
        },

        plotOptions: {
            column: {
                stacking: 'normal'
            }
        },
        credits: { enabled: false },
        series: [
                 @foreach($dashboard->mmActiveListenerCountByPhoneownership(null) as $status => $v)
                       {  name: '{{ $status }}', data: [{{ $v[0] }}, {{ $v[1] }}, {{ $v[2] }}], stack: '{{ $status }}' },
                 @endforeach 
        ]
    });

    function mmacl_callback() 
    {
        var params = mmacl_events_register.getParams();

        $.ajax({
                type: "POST",
                url : base_url + "/chartinfo/mm/activelistenercount",
                data : params,
                success : function(payload) {
                        data = [];
                        console.log(payload["info"]);
                        for(k in payload["info"]) {
                            var v = {
                                name: k,
                                data: [payload["info"][k][0], payload["info"][k][1],payload["info"][k][2]],
                                stack: k
                            };
                            data.push(v);
                        }
                        mmacl_chart.series[0].setData(data);
                }
        });
        return true;
    }

</script>

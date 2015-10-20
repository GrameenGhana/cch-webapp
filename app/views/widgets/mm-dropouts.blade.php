<script type="text/javascript">var mmdropouts_events_register = new EventTracker();</script>

<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Mobile Midwife Coverage: Clients who dropout of Mobile Midwife</h5>
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

                <div class="ibox-content" style="height: 400px;">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="flot-chart">
                                <div style="margin-top: 30px;height: 300px; width: 100%;" id="mmdropouts-chart"></div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <ul class="stat-list">
                                <li>
                                    @include('filters.regiondate', array('prefix'=>'mmdropouts')) 
                                </li>
                                <li>
                                    @include('filters.clienttype', array('prefix'=>'mmdropouts')) 
                                </li>
                                <li>
                                    @include('filters.phoneownership', array('prefix'=>'mmdropouts')) 
                                </li>
                                <li>
                                    @include('filters.parity', array('prefix'=>'mmdropouts')) 
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
    var mmdropouts_chart = new CanvasJS.Chart("mmdropouts-chart",
    {
        title:{ text: ""},
        data: [{
                type: "doughnut",
                legend: {
                    verticalAlign: "bottom",
                    horizontalAlign: "center"
                },
                showInLegend: true,
                indexLabelFontSize: 20,
                indexLabelFontFamily: "Garamond",
                indexLabelFontColor: "darkgrey",
                indexLabelLineColor: "darkgrey",
                indexLabelPlacement: "outside",
                dataPoints:  [
                    @foreach($dashboard->mmDropoutCountByType(true) as $ct => $v)
                    {  y: {{ $v }}, legendText: "{{ $ct.': '.$v }}%", indexLabel: "{{ $ct.': '.$v }}%" },
                    @endforeach
                ]
        }]
    });

    function mmdropouts_callback() 
    {
        var params = mmdropouts_events_register.getParams();
        params['percentage'] = 1;

        $.ajax({
                type: "POST",
                url : base_url + "/chartinfo/mm/dropoutcountbytype",
                data : params,
                success : function(payload) {
                        data = [];
                        for(k in payload["info"]) {
                            var v = {  
                                       y: payload["info"][k], 
                                       legendText: k+": "+payload["info"][k]+"%",
                                       indexLabel: k+": "+payload["info"][k]+"%"
                                    }
                            data.push(v);
                        }
                        mmdropouts_chart.options["data"][0]["dataPoints"]= data;
                        mmdropouts_chart.render();
                }
        });
        return true;
    }

    mmdropouts_chart.render();
</script>

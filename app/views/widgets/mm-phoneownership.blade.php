<script type="text/javascript">var mmpho_events_register = new EventTracker();</script>

<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Mobile Midwife Coverage: Phone Ownership</h5>
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
                                <div style="margin-top: 30px;height: 300px; width: 100%;" id="mmpho-chart"></div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <ul class="stat-list">
                                <li>
                                    @include('filters.regiondate', array('prefix'=>'mmpho')) 
                                </li>
                                <li>
                                    @include('filters.clienttype', array('prefix'=>'mmpho')) 
                                </li>
                                <li>
                                    @include('filters.parity', array('prefix'=>'mmpho')) 
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
    var mmpho_chart = new CanvasJS.Chart("mmpho-chart",
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
                    @foreach($dashboard->mmPhoneownershipCount(true) as $ct => $v)
                    {  y: {{ $v }}, legendText: "{{ $ct.': '.$v }}%", indexLabel: "{{ $ct.': '.$v }}%" },
                    @endforeach
                ]
        }]
    });

    function mmpho_callback() 
    {
        var params = mmpho_events_register.getParams();
        params['percentage'] = 1;

        $.ajax({
                type: "POST",
                url : base_url + "/chartinfo/mm/phoneownershipcount",
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
                        mmpho_chart.options["data"][0]["dataPoints"]= data;
                        mmpho_chart.render();
                }
        });
        return true;
    }

    mmpho_chart.render();
</script>

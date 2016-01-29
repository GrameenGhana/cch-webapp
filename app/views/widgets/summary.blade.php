<style>
*
{
  box-sizing: border-box;
}

/* Line 7 */
h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6
{
  font-family: inherit;
  font-weight: 500;
  line-height: 1.1;
  color: inherit;
}

/* Line 7 */
h1, .h1, h2, .h2, h3, .h3
{
  margin-top: 20px;
  margin-bottom: 10px;
}

/* Line 7 */
h2, .h2
{
  font-size: 30px;
}

/* Line 671 */
h1, h2, h3, h4, h5, h6
{
  font-weight: 100;
}

/* Line 679 */
h2
{
  font-size: 24px;
}

/* Line 145 */
.dashboard-header h2
{
  margin-top: 10px;
  font-size: 26px;
}
/* Line 7 */
small
{
  font-size: 80%;
}

/* Line 7 */
small, .small
{
  font-size: 85%;
}

.stat-list
{
  list-style-type: none;
  list-style-image: none;
  list-style-position: outside;
  padding-top: 0px;
  padding-right: 0px;
  padding-bottom: 0px;
  padding-left: 0px;
  margin-top: 0px;
  margin-right: 0px;
  margin-bottom: 0px;
  margin-left: 0px;
}

/* Line 2756 */
.m-t
{
  margin-top: 15px;
}

/* Line 170 */
ul.clear-list:first-child
{
  border-top-width: medium;
  border-top-style: none;
}

/* Line 2172 */
.stat-list li
{
  margin-top: 15px;
  position: relative;
}

/* Line 2159 */
.stat-list li:first-child
{
  margin-top: 0px;
}

.stat-percent
{
  float: right;
}

/* Line 335 */
.progress-bar
{
  background-color: #1ab394;
}
.progress-mini, .progress-mini .progress-bar
{
  height: 5px;
  margin-bottom: 0px;
}

.ibox
{
  clear: both;
  margin-bottom: 25px;
  margin-top: 0px;
  padding-top: 0px;
  padding-right: 0px;
  padding-bottom: 0px;
  padding-left: 0px;
}

.ibox-title
{
  background-color: #ffffff;
  border-top-color: #e7eaec;
  border-right-color-value: #e7eaec;
  border-bottom-color: #e7eaec;
  border-left-color-value: #e7eaec;
  border-image-source: none;
  border-image-slice: 100% 100% 100% 100%;
  border-image-width: 1 1 1 1;
  border-image-outset: 0 0 0 0;
  border-image-repeat: stretch stretch;
  border-top-style: solid;
  border-right-style-value: solid;
  border-bottom-style: none;
  border-left-style-value: solid;
  border-top-width: 4px;
  border-right-width-value: 0px;
  border-bottom-width: 0px;
  border-left-width-value: 0px;
  color: inherit;
  margin-bottom: 0px;
  padding-top: 14px;
  padding-right: 15px;
  padding-bottom: 7px;
  padding-left: 15px;
}


.ibox-title
{
  background-color: #ffffff;
  border-top-color: #e7eaec;
  border-right-color-value: #e7eaec;
  border-bottom-color: #e7eaec;
  border-left-color-value: #e7eaec;
  border-image-source: none;
  border-image-slice: 100% 100% 100% 100%;
  border-image-width: 1 1 1 1;
  border-image-outset: 0 0 0 0;
  border-image-repeat: stretch stretch;
  border-top-style: solid;
  border-right-style-value: solid;
  border-bottom-style: none;
  border-left-style-value: solid;
  border-top-width: 4px;
  border-right-width-value: 0px;
  border-bottom-width: 0px;
  border-left-width-value: 0px;
  color: inherit;
  margin-bottom: 0px;
  padding-top: 14px;
  padding-right: 15px;
  padding-bottom: 7px;
  padding-left: 15px;
}
.ibox-content h1, .ibox-content h2, .ibox-content h3, .ibox-content h4, .ibox-content h5, .ibox-title h1, .ibox-title h2, .ibox-title h3, .ibox-title h4, .ibox-title h5
{
  margin-top: 5px;
}

/* Line 2418 */
.ibox-title h5
{
  display: inline-block;
  font-size: 14px;
  margin-top: 0px;
  margin-right: 0px;
  margin-bottom: 7px;
  margin-left: 0px;
  padding-top: 0px;
  padding-right: 0px;
  padding-bottom: 0px;
  padding-left: 0px;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.ibox-content
{
  background-color: #ffffff;
  color: inherit;
  padding-top: 15px;
  padding-right: 20px;
  padding-bottom: 20px;
  padding-left: 20px;
  border-top-color: #e7eaec;
  border-right-color-value: #e7eaec;
  border-bottom-color: #e7eaec;
  border-left-color-value: #e7eaec;
  border-image-source: none;
  border-image-slice: 100% 100% 100% 100%;
  border-image-width: 1 1 1 1;
  border-image-outset: 0 0 0 0;
  border-image-repeat: stretch stretch;
  border-top-style: solid;
  border-right-style-value: solid;
  border-bottom-style: none;
  border-left-style-value: solid;
  border-top-width: 1px;
  border-right-width-value: 0px;
  border-bottom-width: 1px;
  border-left-width-value: 0px;
}

/* Line 2405 */
.ibox-content
{
  clear: both;
}
</style>

<div class="row  border-bottom white-bg dashboard-header">
  <div class="col-sm-5">
                        <h2>Welcome 
                            @if (Auth::check()) 
                            {{ Auth::user()->getName() }}
                            @else 
                            {{{ "Guest" }}}
                            @endif
                        </h2>

                        <ul class="list-group clear-list m-t">
                            <li class="list-group-item fist-item" style="background-color: #f3f6fb; padding:10px;">
                                Care Community Hub (CCH), a mobile health initiative in rural Ghana, consists of a mobile app CHNonTheGo which is composed of five inter-related modules:
                            </li>
                            <li class="list-group-item" style="background-color: #f3f6fb; padding:10px;">
                                    <b>Event Planner</b> 
                            </li>
                            <li class="list-group-item" style="background-color: #f3f6fb; padding:10px;">
                                    <b>Point of Care</b> 
                            </li>
                            <li class="list-group-item" style="background-color: #f3f6fb; padding:10px;">
                                    <b>Staying Well</b> 
                            </li>
                            <li class="list-group-item" style="background-color: #f3f6fb; padding:10px;">
                                    <b>Learning Center</b> 
                            </li>
                            <li class="list-group-item" style="background-color: #f3f6fb; padding:10px;">
                                    <b>Achievement Center</b> 
                            </li>
                        </ul>
                    </div>

                    <div class="col-sm-7">
                        
                        <h2>Data Summary</h2>
                        <small>This data was last updated: {{ date('M d, Y h:m:s a',strtotime($dashboard->lastRefreshDate())) }}</small>

                        <div class="row" style="bordeR: 0px solid red;">
                            <div class="col-sm-12">
                                <ul class="stat-list clear-list m-t">
                                        <li>
                                            <h2 class="no-margins">{{ $dashboard->projectLength() }} months</h2>
                                            <small>Length of project</small>

                                            <div class="stat-percent">{{ $dashboard->projectLength(true) }}% <i class="text-navy"></i></div>
                                            <div class="progress progress-mini">
                                                <div style="width: {{ $dashboard->projectLength(true) }}%;" class="progress-bar"></div>
                                            </div>
                                        </li>

                                        <li>
                                            <h2 class="no-margins ">{{ $dashboard->numDistricts() }}</h2>
                                            <small>Number of districts</small>
                                            <div class="stat-percent" title="% of districts in Ghana">{{ $dashboard->numDistricts(true) }}% <i class="text-navy"></i></div>
                                            <div class="progress progress-mini">
                                                <div style="width:{{ $dashboard->numDistricts(true) }}%;" class="progress-bar"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <h2 class="no-margins ">{{ $dashboard->numFacilities() }}</h2>
                                            <small>Number of health facilities</small>
                                            <div class="stat-percent" title="% of health facilities in Ghana">{{ $dashboard->numFacilities(true) }}% <i class="text-navy"></i></div>
                                            <div class="progress progress-mini">
                                                <div style="width:{{ $dashboard->numFacilities(true) }}%;" class="progress-bar"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <h2 class="no-margins">{{ $dashboard->numNurses() }}</h2>
                                            <small>Number of health workers enrolled</small>
                                            <div class="stat-percent" title="% of nurses in Ghana">{{ $dashboard->numNurses(true) }}% <i class="text-navy"></i></div>
                                            <div class="progress progress-mini">
                                                <div style="width: {{ $dashboard->numNurses(true) }}%;" class="progress-bar"></div>
                                            </div>
                                        </li>
                                  </ul>
                              </div>
                            </div>
                        </div>
            </div>

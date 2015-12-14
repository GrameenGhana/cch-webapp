<?php

class IndicatorTrackerController extends BaseController {

    private $rules;

    public function __construct() 
    {
        $this->beforeFilter('auth');

        $this->rules = array('population' => 'required | integer',
                             'district_percentage' => 'required|integer|between:1,100',
                             'zoneselected' => 'required');
    }

    public function index()
    {
        $year = Input::get('year', date('Y'));
        $zone = Input::get('zone', null);

        $years = array();
        for($i=date('Y'); $i >= 2012; $i--) { array_push($years, $i); }

        $districtIds = User::getUserDistricts(Auth::user()->id);
        $locations = District::with('subdistricts.zones')->whereIn('id', explode(',',$districtIds))->get();
        $zone = ($zone==null) ? $this->getFirstZone($locations) : $zone;
        $zonedata = IndicatorTracker::ZoneActuals($zone, $year);

        //return Response::json($zonedata);

        //print '<pre>'; print_r($pops[0]->zone->name);print '</pre>';exit;
        return View::make('targets.actuals.index', array('locations' => $locations,
                                                         'data' => $zonedata,
                                                         'year' => $year, 
                                                         'years'=>$years));
    }

    private function getFirstZone($locations)
    {
        $id = null;

        foreach($locations as $district)
        {
            foreach($district->subdistricts as $sd) {
                foreach($sd->zones as $z) {
                    if ($id == null) { $id = $z->id; }
                }
            }
        }

        return $id;
    }
}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ZonePopulationController
 *
 * @author liman
 */
class ZonePopulationController extends \BaseController {

    public function __construct() {
        $this->beforeFilter('auth');

        $region = District::groupBy("region")->get();
        $districts = District::all();
        $subdistricts = SubDistrict::all();
        $zonelists = Zone::all();
        $this->districtIds = User::getUserDistricts(Auth::user()->id);
        $this->regions = array();
        $this->districts = array();
        $this->subdistricts = array();
        $this->zones = array();
        $this->isDistrictAdmin = false;

        if (strtolower(Auth::user()->role) == 'district admin' || 
            strpos(strtolower(Auth::user()->role), "supervisor") != '') {

            $this->isDistrictAdmin = true;
            $districts = District::whereRaw(' id  in (?) ', array($this->districtIds))->get();
            $region = User::getUserRegions(Auth::user()->id);
            $subdistricts = SubDistrict::whereRaw(' district_id  in (?) ', 
                                                    array($this->districtIds))->get();
            $zonelists = Zone::whereRaw(' district_id  in (?) ', array($this->districtIds))->get();
        }

        foreach ($zonelists as $k => $v) {
            $this->zones[$v->zone][$v->id] = $v->name;
        }

        foreach ($region as $key => $value) {
           if ($value->region!='unknown') {
               $this->regions[$value->region] = $value->region;
           }
        }

        foreach ($districts as $k => $v) {
            $this->districts[$v->district][$v->id] = $v->name;
        }

        foreach ($subdistricts as $k => $v) {
            $this->subdistricts[$v->subdistrict][$v->id] = $v->name;
        }

        $this->years = array();
        for($i=2012; $i <=date('Y'); $i++) { $this->years[$i] = $i; };

        $this->rules = array('population' => 'required | integer', 
                             'district_percentage' => 'required|integer|between:1,100', 
                             'zoneselected' => 'required');
    }

//	private $regions;
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() 
    {
	    $year = Input::get('year');
        $currentyear = date('Y');
        
        if(null != $year){ $currentyear = $year;  }

        if ($this->isDistrictAdmin) {
            $pops = ZonePopulation::whereRaw('year=? and district_id  in (?) ', 
                                              array($currentyear,$this->districtIds))->get();
        } else {
            $pops = ZonePopulation::where('year','=',$currentyear)->get();
        }
        
        //print '<pre>'; print_r($pops[0]->zone->name);print '</pre>';exit;
        return View::make('targets.zonepopulations.index', array('zones' => $pops,'year' => $currentyear));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return View::make('targets.zonepopulations.create', array("zones" => $this->zones, "districts" => $this->districts, "region" => $this->regions, "subdistricts" => $this->subdistricts, "years"=>$this->years));   //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $validator = Validator::make(Input::all(), $this->rules);

        if ($validator->fails()) {
            return Redirect::to('/target/population/zones/create')
                            ->with('flash_error', 'true')
                            ->withErrors($validator);
        } else {
            $pop = new ZonePopulation;
            $pop->id = Input::get('zoneselected');
            $pop->region = Input::get('region');
            $pop->district_id = Input::get('district');
            $pop->subdistrict_id = Input::get('subdistrict');
            $pop->zone_id = Input::get('zone');
            $pop->year = Input::get('year');
            $pop->population = Input::get('population');
            $pop->district_percentage = Input::get('district_percentage');
            $pop->expected_pregnancies = Input::get('expected_pregnancies');
//            $pop->chn_6_11_mnths = Input::get('chn_6_11_mnths');
            $pop->chn_0_to_11_mnths = Input::get('chn_0_to_11_mnths');
            $pop->chn_12_23_mnths = Input::get('chn_12_23_mnths');
//            $pop->chn_0_to_23_mnths = Input::get('chn_0_to_23_mnths');
            $pop->chn_24_to_59_mnths = Input::get('chn_24_to_59_mnths');
            $pop->chn_less_than_5_yrs = Input::get('chn_less_than_5_yrs');
            $pop->wifa_15_49_yrs = Input::get('wifa_15_49_yrs');
            $pop->men_women_50_to_60_yrs = Input::get('men_women_50_to_60_yrs');
            $pop->modified_by = 1;


            $zone_pops = ZonePopulation::whereRaw('year = ? and subdistrict_id = ? ', array($pop->year, $pop->subdistrict_id))->get();
            $zone_populations_total = "";
            $zone_populations = array();

            if (null != $zone_pops) {

                foreach ($zone_pops as $key => $value) {
                    $zone_populations[$value->id] = $value->population;
                }

                $zone_populations_total = array_sum($zone_populations) + $pop->population;
                Log::info("Total population of zone -> " . $pop->population);
                Log::info("Total population of subdistrict -> " . array_sum($zone_populations));
                Log::info("Total population of subdistrict after calculation -> " . $zone_populations_total);


                $popsb = SubDistrictPopulation::whereRaw('year = ? and subdistrict_id = ? ', array($pop->year, $pop->subdistrict_id))->first();

                if ($popsb != null) {

                    if ($popsb->population < $zone_populations_total) {
                        return Redirect::to('/targets/population/zones/create')
                                        ->with('flash_error', 'true')
                                        ->withErrors("Population doess not tally with totals of sub district selected ");
                    } else {
                        $pop->save();
                        Session::flash('message', "{$pop->zone->name } : {$pop->population} created successfully");
                        return Redirect::to('/targets/population/zones');
                    }
                }//end if
                else {
                    return Redirect::to('/targets/population/zones/create')
                                    ->with('flash_error', 'true')
                                    ->withErrors("Population of subdistrict not found. ");
                }
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
//
        $pop = ZonePopulation::find($id);
        return View::make('targets.zonepopulations.edit', array("subdistricts" => $this->subdistricts,"years"=>$this->years));   //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
//
        $pop = ZonePopulation::find($id);
        return View::make('targets.zonepopulations.edit', array('pop' => $pop, 'zones' => $this->zones, "districts" => $this->districts, "region" => $this->regions, "subdistricts" => $this->subdistricts,"years"=>$this->years));   //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) 
    {
        $validator = Validator::make(Input::all(), $this->rules);

        if ($validator->fails()) {
            return Redirect::to('/targets/population/zones/' . $id . '/edit')
                            ->with('flash_error', 'true')
                            ->withErrors($validator);
        } else {
            $pop = ZonePopulation::find($id);
            $pop->zone_id = Input::get('zoneselected');
            $pop->region = Input::get('region');
            $pop->district_id = Input::get('district');
            $pop->subdistrict_id = Input::get('subdistrict');
            $pop->year = Input::get('year');
            $pop->population = Input::get('population');
            $pop->district_percentage = Input::get('district_percentage');
            $pop->expected_pregnancies = Input::get('expected_pregnancies');
            $pop->chn_0_to_11_mnths = Input::get('chn_0_to_11_mnths');
            $pop->chn_12_23_mnths = Input::get('chn_12_23_mnths');
            $pop->chn_24_to_59_mnths = Input::get('chn_24_to_59_mnths');
            $pop->chn_less_than_5_yrs = Input::get('chn_less_than_5_yrs');
            $pop->wifa_15_49_yrs = Input::get('wifa_15_49_yrs');
            $pop->men_women_50_to_60_yrs = Input::get('men_women_50_to_60_yrs');

            $zone_pops = ZonePopulation::whereRaw('year = ? and subdistrict_id = ? ', 
                                                    array($pop->year, $pop->subdistrict_id))->get();

            if (null != $zone_pops) {

                $zone_populations = array();
                $zone_populations_total = 0;

                foreach ($zone_pops as $key => $value) {
                    if ($value->id != $pop->id) {
                        $zone_populations[$value->id] = $value->population;
                    }
                }

                $zone_populations_total = array_sum($zone_populations) + $pop->population;
                Log::info("Total population of zone -> " . $pop->population);
                Log::info("Total population of subdistrict -> " . array_sum($zone_populations));
                Log::info("Total population of subdistrict after calculation -> ".$zone_populations_total);


                $popsb = SubDistrictPopulation::whereRaw('year = ? and subdistrict_id = ? ', 
                                            array($pop->year, $pop->subdistrict_id))->first();

                if ($popsb != null) {

                    if ($popsb->population < $zone_populations_total) {
                        return Redirect::to('/targets/population/zones/' . $id . '/edit')
                             ->with('flash_error', 'true')
                             ->withErrors("Population does not tally with totals of sub district selected");
                    } else {
                        $pop->save();
                        Session::flash('message', "{$pop->zone->name} saved successfully");
                        return Redirect::to('/targets/population/zones/'.$id.'/edit');
                    }
                } else {
                    return Redirect::to('/targets/population/zones/' . $id . '/edit')
                                    ->with('flash_error', 'true')
                                    ->withErrors("Population of subdistrict not found. ");
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
//
    }

    public function updateAll() {
//
        $validator = Validator::make(Input::all(), $this->rules);

        $type = Input::get("type");
        $typeId = Input::get("typeId");
        $year = Input::get("year");


        $pops = ZonePopulation::whereRaw("year = ? and $type = ? ", array($year, $typeId))->get();
        foreach ($pops as $pop) {
            $valueId = $pop->id;

            $pop->population = Input::get("population_$valueId");
            $pop->district_percentage = Input::get("district_percentage_$valueId");
            $pop->expected_pregnancies = Input::get("expected_pregnancies_$valueId");
//            $pop->chn_6_11_mnths = Input::get("chn_6_11_mnths_$valueId");
            $pop->chn_0_to_11_mnths = Input::get("chn_0_to_11_mnths_$valueId");
            $pop->chn_12_23_mnths = Input::get("chn_12_23_mnths_$valueId");
//            $pop->chn_0_to_23_mnths = Input::get("chn_0_to_23_mnths_$valueId");
            $pop->chn_24_to_59_mnths = Input::get("chn_24_to_59_mnths_$valueId");
            $pop->chn_less_than_5_yrs = Input::get("chn_less_than_5_yrs_$valueId");
            $pop->wifa_15_49_yrs = Input::get("wifa_15_49_yrs_$valueId");
            //$pos->chn_less_than_5_yrs = Input::get("chn_less_than_5_yrs_$valueId");
            $pop->men_women_50_to_60_yrs = Input::get("men_women_50_to_60_yrs_$valueId");
            $pop->save();
        }

        return Redirect::to('/targets/population/zones');
//        }
    }

    /**
     * @param type $districtId
     * @param type $year
     */
    public function districtView($districtId, $year) {


        $district = District::find($districtId);
        $districtPopulation = DistrictPopulation::whereRaw('year = ? and district_id = ? ', array($year, $districtId))->first();

        $rawResult = (array) DB::select("select z.* from cch_zones z left join cch_zone_population zp  on z.id= zp.zone_id and zp.year='$year' where   zp.zone_id is  null and z.district_id='$districtId' ");
        $zones = Zone::hydrate($rawResult);
        $this->saveDefaultInsert($zones, $year);
        $pops = ZonePopulation::whereRaw('year = ? and district_id = ? ', array($year, $districtId))->get();
        return View::make('targets.zonepopulations.bulkedit', array('zones' => $pops,
                    "districtpopulation" => $districtPopulation,
                    'district' => $district,
                    "type" => "district_id", "year" => $year, "typeId" => $districtId));
    }

    public function subDistrictView($districtId, $year)
    {
        $subDistrict = SubDistrict::find($districtId);
        //print '<pre>'; print_r($subDistrict); print '</pre>'; exit;
        
        if ($subDistrict != null)
        {
            $subDistrictPopulation = SubDistrictPopulation::whereRaw('year = ? and subdistrict_id = ? ', 
                                                                      array($year, $districtId))->first();
            $districtPopulation = DistrictPopulation::whereRaw('year = ? and district_id = ? ', 
                                                        array($year, $subDistrict->district_id))->first();

            $district = District::find($subDistrict->district_id);

            $rawResult = (array) DB::select("select z.* 
                                               from cch_zones z 
                                               left join cch_zone_population zp on z.id= zp.zone_id 
                                                and zp.year='$year' 
                                              where zp.zone_id is null 
                                                and z.subdistrict_id='" . $subDistrict->id . "'");
            $zones = Zone::hydrate($rawResult);
            $this->saveDefaultInsert($zones, $year);
            $pops = ZonePopulation::whereRaw('year = ? and subdistrict_id = ? ', 
                                                array($year, $districtId))->get();
        
            return View::make('targets.zonepopulations.bulkedit', array('zones' => $pops,
                    'district' => $district,
                    'subdistrict' => $subDistrict,
                    "districtpopulation" => $districtPopulation,
                    "subdistrictpopulation" => $subDistrictPopulation, 
                    "type" => "subdistrict_id", "year" => $year, "typeId" => $districtId));
        } 

        return Redirect::to('/targets/population/subdistricts')
                            ->with('flash_error', 'true')
                            ->withErrors("Cannot find subdistrict zones");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function indexAll($year) {
        //  'Zone','zone_id','zone_id');
        $rawResult = (array) DB::select("select z.* from cch_zones z left join cch_zone_population zp  on z.zone_id= zp.zone_id and zp.year='$year' where   zp.zone_id is  null ");
        $zones = Zone::hydrate($rawResult);
        $this->saveDefaultInsert($zones);
        $pops = ZonePopulation::hydrate((array) DB::select("select * from cch_zone_population where   year=$year"));
        return View::make('targets.zonepopulations.bulkedit', array('zones' => $pops, 'year' => $year));
    }

    function saveDefaultInsert($zones, $year) {
        foreach ($zones as $zone) {
            $pop = new ZonePopulation;
            $pop->zone_id = $zone->id;
            $pop->year = $year;
            $pop->population = 0;
            $pop->district_percentage = 0;
            $pop->expected_pregnancies = 0;
            $pop->chn_0_to_11_mnths = 0;
            $pop->chn_12_23_mnths = 0;
            $pop->chn_24_to_59_mnths = 0;
            $pop->chn_less_than_5_yrs = 0;
            $pop->wifa_15_49_yrs = 0;
            $pop->men_women_50_to_60_yrs = 0;
            $pop->modified_by = 1;
            $pop->region = $zone->region;
            $pop->district_id = $zone->district_id;
            $pop->subdistrict_id = $zone->subdistrict_id;
            $pop->save();
        }
    }
}

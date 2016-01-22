<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SubDistrictPopulationController
 *
 * @author liman
 */
class SubDistrictPopulationController extends \BaseController {

    public function __construct() {


        $this->regions = array();
        $this->districts = array();
         $this->districtIds = User::getUserDistricts(Auth::user()->id);
        $this->isDistrictAdmin = false;

        if (strtolower(Auth::user()->role) == 'district admin' || strpos(strtolower(Auth::user()->role), "supervisor") != '') {
            $ids = User::getUserDistricts(Auth::user()->id);

             $this->isDistrictAdmin = true;
             $districts = District::whereRaw(' id  in (?) ', array($ids))->get();
             $region = User::getUserRegions(Auth::user()->id);
             $subdistricts = SubDistrict::whereRaw(' district_id  in (?) ', array($this->districtIds))->get();
        } else {
             $districts = District::all();
             $region = District::groupBy("region")->get();
             $subdistricts = SubDistrict::all();
        }


        foreach ( $region as $key => $value) {
            $this->regions[$value->region] = $value->region;
        }


        foreach ($districts as $k => $v) {
            $this->districts[$v->district][$v->id] = $v->name;
        }

      $this->years = array();
        for($i=2012; $i <=date('Y'); $i++) { $this->years[$i] = $i; };


        $this->subdistricts = array();

        foreach ($subdistricts as $k => $v) {
            $this->subdistricts[$v->subdistrict][$v->id] = $v->name;
        }


        $this->rules = array('population' => 'required', 'district' => 'required', 'subdistrict' => 'required');
    }

//	private $regions;
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
 	$year = Input::get('year');
        $currentyear = date('Y');
        if(null != $year){
          $currentyear = $year;  
        }
  
      if ($this->isDistrictAdmin) {
            $pops = SubDistrictPopulation::whereRaw('year=? and  district_id  in (?) ', array($currentyear,$this->districtIds))->get();
        } else
            $pops = SubDistrictPopulation::where('year','=',$currentyear)->get();
        return View::make('targets.subdistrictpopulations.index', array('pops' => $pops,'year' => $currentyear));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return View::make('targets.subdistrictpopulations.create', array("districts" => $this->districts, "subdistricts" => $this->subdistricts, "regions" => $this->regions, "years"=>$this->years));   //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $validator = Validator::make(Input::all(), $this->rules);



        if ($validator->fails()) {
            return Redirect::to('/target/population/subdistricts/create')
                            ->with('flash_error', 'true')
                            ->withErrors($validator);
        } else {
            $pop = new SubDistrictPopulation;
            $pop->region = Input::get('region');
            $pop->district_id = Input::get('district');
            $pop->subdistrict_id = Input::get('subdistrict');
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
            $pop->save();
            Session::flash('message', "{$pop->subdistrict->name } -  {$pop->population} created successfully");
            return Redirect::to('/targets/population/subdistricts');
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
        $pop = SubDistrictPopulation::find($id);
        return View::make('targets.subdistrictpopulations.edit', array("subdistricts" => $this->subdistricts));   //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) 
    {
        $pop = SubDistrictPopulation::find($id);
        return View::make('targets.subdistrictpopulations.edit', 
                         array('pop' => $pop, "districts" => $this->districts, "years"=>$this->years, 
                               "subdistricts" => $this->subdistricts, "regions" => $this->regions));   //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
//
        $validator = Validator::make(Input::all(), $this->rules);

        if ($validator->fails()) {
            return Redirect::to('/targets/populations/subdistricts/' . $id . '/edit')
                            ->with('flash_error', 'true')
                            ->withErrors($validator);
        } else {
            $pop = SubDistrictPopulation::find($id);
            $pop->region = Input::get('region');
            $pop->district_id = Input::get('district');
            $pop->subdistrict_id = Input::get('subdistrict');
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
            $pop->save();

            Session::flash('message', "{$pop->subdistrict->name } :  {$pop->population} edited successfully");
            return Redirect::to('targets/population/subdistricts');
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getSubDistrictTotalPopulation() {
//
        $year = Input::get('y');
        $subdistrict = Input::get('subdistrict');
        $zonepopulation = Input::get('population');

        $zone_pops = ZonePopulation::whereRaw('year = ? and subdistrict_id = ? ', array($year, $subdistrict))->get();
        $zone_populations_total = "";
        $zone_populations = array();

        if (null != $zone_pops) {

            foreach ($zone_pops as $key => $value) {
                $zone_populations[$value->id] = $value->population;
            }

            $zone_populations_total = array_sum($zone_populations) + $zonepopulation;
            Log::info("Total population of subdistrict -> " . $zone_populations_total);


            $pop = SubDistrictPopulation::whereRaw('year = ? and subdistrict_id = ? ', array($year, $subdistrict))->first();

            if ($pop->population < $zone_populations_total) {
                return null;
            } else {
                return Response::json($pop);
            }
        }

        return null;
    }

    public function districtView($districtId, $year) {

        $district = District::find($districtId);
        $districtPopulation = DistrictPopulation::whereRaw('year = ? and district_id = ? ', array($year, $districtId))->first();

        $rawResult = (array) DB::select("select z.* from cch_sub_districts z left join cch_sub_district_population zp  on z.id= zp.subdistrict_id and zp.year='$year' where   zp.district_id is  null and z.district_id='$districtId' ");
        $sd = SubDistrict::hydrate($rawResult);
        $this->saveDefaultInsert($sd, $year);
        $pops = SubDistrictPopulation::whereRaw('year = ? and district_id = ? ', array($year, $districtId))->get();
        return View::make('targets.subdistrictpopulations.bulkedit', array(
                    'subdistricts' => $pops,
                    "districtpopulation" => $districtPopulation,
                    "type" => "district_id",
                    "district" => $district,
                    "year" => $year,
                    "typeId" => $districtId));
    }

    /**
     * 
     * Bulk Update
     * @return type
     */
    public function updateAll() {
//
        $validator = Validator::make(Input::all(), $this->rules);

        $type = Input::get("type");
        $typeId = Input::get("typeId");
        $year = Input::get("year");


        $pops = SubDistrictPopulation::whereRaw("year = ? and district_id = ? ", array($year, $typeId))->get();
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
            $pop->chn_less_than_5_yrs = Input::get("chn_less_than_5_yrs_$valueId");
            $pop->men_women_50_to_60_yrs = Input::get("men_women_50_to_60_yrs_$valueId");
            $pop->save();
        }

        return Redirect::to('targets/population/subdistricts');
//        }
    }

    /**
     * Saving the Default when doing bulk set all values to 0
     * @param type $subDistricts
     * @param type $year
     */
    function saveDefaultInsert($subDistricts, $year) {

        foreach ($subDistricts as $subDistrict) {
            $pop = new SubDistrictPopulation;
            $pop->year = $year;
            $pop->population = 0;
            $pop->district_percentage = 0;
            $pop->expected_pregnancies = 0;
//            $pop->chn_6_11_mnths = 0;
            $pop->chn_0_to_11_mnths = 0;
            $pop->chn_12_23_mnths = 0;
//            $pop->chn_0_to_23_mnths = 0;
            $pop->chn_24_to_59_mnths = 0;
            $pop->chn_less_than_5_yrs = 0;
            $pop->wifa_15_49_yrs = 0;
            $pop->chn_less_than_5_yrs = 0;
            $pop->men_women_50_to_60_yrs = 0;
            $pop->modified_by = 1;
            $pop->region = $subDistrict->region;
            $pop->district_id = $subDistrict->district_id;
            $pop->subdistrict_id = $subDistrict->id;
            if ($pop->subdistrict_id > 0)
                $pop->save();
        }
    }

}


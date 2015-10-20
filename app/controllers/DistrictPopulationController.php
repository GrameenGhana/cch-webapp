<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DistrictPopulationController
 *
 * @author liman
 */
class DistrictPopulationController extends \BaseController {

    public function __construct() {

         $this->regions = array();
 if (strtolower(Auth::user()->role) == 'district admin' || strpos(strtolower(Auth::user()->role),"supervisor")!='') {
           $ids = User::getUserDistricts(Auth::user()->id);

$districts= District::whereRaw(' id  in (?) ',array($ids) )->get();
$region = User::getUserRegions(Auth::user()->id);

}else  {
$districts = District::all();

         $region = District::groupBy("region")->get();
}        foreach ($region as $key => $value) {
            $this->regions[$value->region] = $value->region;
        }


//        $districts = District::all();
        
        $this->districts = array();

        foreach ($districts as $k => $v) {
            $this->districts[$v->district][$v->id]= $v->name;
        }

        

        $this->rules = array('year' => 'required', 'district' => 'required' ,'population' => 'required|integer');
    }

//	private $regions;
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
//        $pops = DistrictPopulation::all();
	$year = Input::get('year');
        $currentyear = date('Y');
        if(null != $year){
          $currentyear = $year;  
        }  

 if (strtolower(Auth::user()->role) == 'district admin' || strpos(strtolower(Auth::user()->role),"supervisor")!='') {
           $ids = User::getUserDistricts(Auth::user()->id);
           $pops = DistrictPopulation::whereRaw('year =? and  district_id  in (?) ',array($currentyear,$ids) )->get();
        } else{
            $pops = DistrictPopulation::where('year','=',$currentyear)->get();
        }
      return View::make('districtpopulations.index', array('pops' => $pops,'year' => $currentyear));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return View::make('districtpopulations.create', array("districts" => $this->districts, "region" => $this->regions));   //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $validator = Validator::make(Input::all(), $this->rules);

      

        if ($validator->fails()) {
            return Redirect::to('/districtpopulations/create')
                            ->with('flash_error', 'true')
                            ->withErrors($validator);
        } else {
            $pop = new DistrictPopulation;
            $pop->region = Input::get('region');
            $pop->district_id = Input::get('district');
            $pop->year = Input::get('year');
            $pop->population = Input::get('population');
            $pop->expected_pregnancies = Input::get('expected_pregnancies');
            $pop->chn_6_11_mnths = Input::get('chn_6_11_mnths');
            $pop->chn_0_to_11_mnths = Input::get('chn_0_to_11_mnths');
            $pop->chn_12_23_mnths = Input::get('chn_12_23_mnths');
            $pop->chn_0_to_23_mnths = Input::get('chn_0_to_23_mnths');
            $pop->chn_24_to_59_mnths = Input::get('chn_24_to_59_mnths');
            $pop->chn_less_than_5_yrs = Input::get('chn_less_than_5_yrs');
            $pop->wifa_15_49_yrs = Input::get('wifa_15_49_yrs');
            $pop->men_women_50_to_60_yrs = Input::get('men_women_50_to_60_yrs');
            $pop->modified_by = 1;
            $pop->save();
            Session::flash('message', "Population of {$pop->district->name} - {$pop->population} created successfully");
            return Redirect::to('/districtpopulations');
        }
    }
 
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getDistrictTotalPopulation() {
//
        $year = Input::get( 'y' );
        $district = Input::get( 'district' );
        $district_percentage = Input::get( 'percentage' );

        $subdistrict_pops = SubDistrictPopulation::whereRaw('year = ? and district_id = ? ',array($year,$district) )->get();
        $subdistrict_percentages_total = "";
        $subdistrict_percentages = array();

        if(null != $subdistrict_pops) {

        foreach ($subdistrict_pops as $key => $value) {
            $subdistrict_percentages[$value->id] = $value->district_percentage;
        }

        $subdistrict_percentages_total = array_sum($subdistrict_percentages) + $district_percentage;
        Log::info("Total percentage of subdistricts -> " . $subdistrict_percentages_total );

        if($subdistrict_percentages_total > 100){
            return null;
        }else{

            $pop = DistrictPopulation::whereRaw('year = ? and district_id = ? ',array($year,$district) )->first();

            return Response::json($pop);
        }

    }else{
        Log::info("No subdistricts retrieved for district  id-> " . $district);
        $pop = DistrictPopulation::whereRaw('year = ? and district_id = ? ',array($year,$district) )->first();

            return Response::json($pop);
    }

       return null;
        
    }



    public function getDistrictTotalPopulationZones() {
//
        $year = Input::get( 'y' );
        $district = Input::get( 'district' );


        $pop = DistrictPopulation::whereRaw('year = ? and district_id = ? ',array($year,$district) )->first();

        return Response::json($pop);
        
        
    }


   

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
//
        $pop = DistrictPopulation::find($id);
        return View::make('districtpopulations.edit', array('pop'=>$pop,'districts'=>$this->districts, "region" => $this->regions));   //
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
            return Redirect::to('/districtpopulations/'.$id.'/edit')
                            ->with('flash_error', 'true')
                            ->withErrors($validator);
        } else {
            $pop = DistrictPopulation::find($id);
            $pop->region = Input::get('region');
            $pop->district_id = Input::get('district');
            $pop->year = Input::get('year');
            $pop->population = Input::get('population');
            $pop->expected_pregnancies = Input::get('expected_pregnancies');
            $pop->chn_6_11_mnths = Input::get('chn_6_11_mnths');
            $pop->chn_0_to_11_mnths = Input::get('chn_0_to_11_mnths');
            $pop->chn_12_23_mnths = Input::get('chn_12_23_mnths');
            $pop->chn_0_to_23_mnths = Input::get('chn_0_to_23_mnths');
            $pop->chn_24_to_59_mnths = Input::get('chn_24_to_59_mnths');
            $pop->chn_less_than_5_yrs = Input::get('chn_less_than_5_yrs');
            $pop->wifa_15_49_yrs = Input::get('wifa_15_49_yrs');
            $pop->men_women_50_to_60_yrs = Input::get('men_women_50_to_60_yrs');
            $pop->save();
            Session::flash('message', "{$pop->district->name} : {$pop->population} edited successfully");
            return Redirect::to('/districtpopulations');
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
}


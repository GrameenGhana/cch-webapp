<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ZoneController
 *
 * @author liman
 */
class ZoneController extends \BaseController {
/**
 * $region = District::groupBy("region")->get();
        $districts = District::all();
        $subdistricts = SubDistrict::all();
        
        
        $this->regions = array();
        $this->districts = array();
        $this->subdistricts = array();
        $this->facilities = array();

        foreach ($subdistricts as $k => $v) {
            $this->subdistricts[$v->subdistrict][$v->id]= $v->name;
        }

        foreach ($facilities as $k => $v) {
            $this->facilities[$v->facility][$v->id]= $v->name;
        }

        foreach ($region as $key => $value) {
            $this->regions[$value->region] = $value->region;
        }

        foreach ($districts as $k => $v) {
            $this->districts[$v->district][$v->id]= $v->name;
        }

 */
    public function __construct() {
        $region = District::groupBy("region")->get();
        $districts = District::all();
        $subdistricts = SubDistrict::all();
        $zonelists = Zone::all();
        $this->districtIds = User::getUserDistricts(Auth::user()->id);
        $this->regions = array();
        $this->districts = array();
        $this->subdistricts = array();
        $this->zones = array();
            $this->isDistrictAdmin =false;
       

        if (strtolower(Auth::user()->role) == 'district admin' || strpos(strtolower(Auth::user()->role), "supervisor") != '') {

            $this->isDistrictAdmin = true;
            $districts = District::whereRaw(' id  in (?) ', array($this->districtIds))->get();
            $region = User::getUserRegions(Auth::user()->id);
            $subdistricts = SubDistrict::whereRaw(' district_id  in (?) ', array($this->districtIds))->get();
            $zonelists = Zone::whereRaw(' district_id  in (?) ', array($this->districtIds))->get();
             $facilities = Facility::whereRaw(' district  in (?) ', array($this->districtIds))->get();
        }else{
            
             $facilities = Facility::all();
        }

        foreach ($zonelists as $k => $v) {
            $this->zones[$v->zone][$v->id] = $v->name;
        }

        foreach ($region as $key => $value) {
            $this->regions[$value->region] = $value->region;
        }

        foreach ($districts as $k => $v) {
            $this->districts[$v->district][$v->id] = $v->name;
        }

        foreach ($subdistricts as $k => $v) {
            $this->subdistricts[$v->subdistrict][$v->id] = $v->name;
        }

	foreach ($facilities as $k => $v) {
            $this->facilities[$v->facility][$v->id]= $v->name;
        }

        $this->rules = array('name' => 'required|min:3', 'subdistrict' => 'required', 'facility' => 'required');
    }

//	private $regions;
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        if ($this->isDistrictAdmin) {
            $zones = Zone::whereRaw(' district_id  in (?) ', array($this->districtIds))->get();
        } else
            $zones = Zone::all();
        return View::make('zones.index', array('zones' => $zones));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return View::make('zones.create', array("districts" => $this->districts, "region" => $this->regions, "subdistricts" => $this->subdistricts, "facilities" => $this->facilities));   //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $validator = Validator::make(Input::all(), $this->rules);



        if ($validator->fails()) {
            return Redirect::to('/zones/create')
                            ->with('flash_error', 'true')
                            ->withErrors($validator);
        } else {
            $zone = new Zone;
            $zone->name = Input::get('name');
            $zone->region = Input::get('region');
            $zone->district_id = Input::get('district');
            $zone->subdistrict_id = Input::get('subdistrict');
            $zone->facility_id = Input::get('facility');
            $zone->modified_by = 1;
            $zone->save();
            Session::flash('message', "{$zone->name} created successfully");
            return Redirect::to('/zones');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
//
        $zone = Zone::find($id);
        return View::make('zones.edit', array('zone' => $zone, "districts" => $this->districts, "regions" => $this->regions, 'subdistricts' => $this->subdistricts, "facilities" => $this->facilities));   //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $validator = Validator::make(Input::all(), $this->rules);

        if ($validator->fails()) {
            return Redirect::to('/zones/' . $id . '/edit')
                            ->with('flash_error', 'true')
                            ->withErrors($validator);
        } else {
            $zone = Zone::find($id);
            $zone->name = Input::get('name');
            $zone->region = Input::get('region');
            $zone->district_id = Input::get('district');
            $zone->subdistrict_id = Input::get('subdistrict');
            $zone->facility_id = Input::get('facility');

            $zone->save();
            Session::flash('message', "{$zone->name} edited successfully");
            return Redirect::to('/zones');
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

    public function getZones() {
//
        $subdistrict = Input::get('subdistrict');

        if (!$subdistrict) {
            return false;
        }

        $options = "";
        $lists = Zone::whereRaw('subdistrict_id= ? ', array($subdistrict))->get();


        foreach ($lists as $k => $v) {
            $id = $v->id;
            $data = $v->name;
            $options.= '<option value="' . $id . '">' . $data . '</option>';
        }

        return $options;
    }
    public function get3Zones() {
//
        $subdistrict = Input::get('subdistrict');

        if (!$subdistrict) {
            return false;
        }

        $options = "";
        $lists = Zone::whereRaw('subdistrict_id= ? ', array($subdistrict))->get();


        foreach ($lists as $k => $v) {
            $id = $v->id;
            $data = $v->name;
            $options.= '<option value="' . $id . '">' . $data . '</option>';
        }

        return $options;
    }

    public function getByFacilityZones() {
//
        $subdistrict = Input::get('facility');

        if (!$subdistrict) {
            return false;
        }

        $options = "<option value='0'>Un Known</option>";
        $lists = Zone::whereRaw('facility_id= ? ', array($subdistrict))->get();


        foreach ($lists as $k => $v) {
            $id = $v->id;
            $data = $v->name;
            $options.= '<option value="' . $id . '">' . $data . '</option>';
        }

        return $options;
    }


}


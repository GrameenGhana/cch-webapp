<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SubDistrictController
 *
 * @author skwakwa
 */
class SubDistrictController extends \BaseController {

    public function __construct() {
        $this->regions = array();
        $this->isDistrictAdmin = false;

        $this->districtIds = User::getUserDistricts(Auth::user()->id);
        if (strtolower(Auth::user()->role) == 'district admin' || strpos(strtolower(Auth::user()->role), "supervisor") != '') {
            $districts = District::whereRaw(' id  in (?) ', array($this->districtIds))->get();
            $this->isDistrictAdmin = true;
        } else {
            $districts = District::all();
        }
        $this->regions = array();
        //$this->pfacilities = array('Other' => 'Unknown');

        foreach ($districts as $k => $v) {
            //    if (in_array($v->region, array_keys($this->regions))) {
            $this->regions[$v->region][$v->id] = $v->name;
            //  } else {
            //    $this->regions[$v->region] = array($v->id=>$v->name);
            // }
        }




        $this->rules = array('name' => 'required|min:3', 'district' => 'required');
    }

//	private $regions;
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        if ($this->isDistrictAdmin) {
            $districts = SubDistrict::whereRaw(' district_id  in (?) ', array($this->districtIds))->get();
        } else
            $districts = SubDistrict::all();
        return View::make('subdistricts.index', array('subdistricts' => $districts));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return View::make('subdistricts.create', array("districts" => $this->regions));   //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $validator = Validator::make(Input::all(), $this->rules);



        if ($validator->fails()) {
            return Redirect::to('/subdistricts/create')
                            ->with('flash_error', 'true')
                            ->withErrors($validator);
        } else {
            $subDistrict = new SubDistrict;
            $subDistrict->name = Input::get('name');
            $subDistrict->district_id = Input::get('district');
            $subDistrict->modified_by = 1;
            $subDistrict->save();
            Session::flash('message', "{$subDistrict->name} created successfully");
            return Redirect::to('/subdistricts');
        }
    }

    public function showPeople($id) {
        $districts = SubDistrict::find($id);
        //  return View::make('districts.people', array('district' => $districts));
        $rawResult = (array) DB::select("select u.* from cch_users u inner join cch_facility_user cfu on u.id=cfu.user_id and cfu.primary=1  inner join cch_facilities cf on cfu.facility_id = cf.id where cf.sub_district='$id'");
        $users = User::hydrate($rawResult);

        return View::make('subdistricts.people', array('subdistrict' => $districts, 'users' => $users));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
//
        $district = SubDistrict::find($id);
        return View::make('subdistricts.edit', array("districts" => $this->regions));   //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
//
        $district = SubDistrict::find($id);
        return View::make('subdistricts.edit', array('subdistrict' => $district, "districts" => $this->regions));   //
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
            return Redirect::to('/subdistricts/' . $id . '/edit')
                            ->with('flash_error', 'true')
                            ->withErrors($validator);
        } else {
            $district = SubDistrict::find($id);
            $district->name = Input::get('name');

            $district->district_id = Input::get('district');

            $district->save();
            Session::flash('message', "{$district->name} edited successfully");
            return Redirect::to('/subdistricts');
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

    public function getSubDistricts() {
//
        $district = Input::get('district');

        if (!$district) {
            return false;
        }

        $options = "";
        $lists = SubDistrict::whereRaw('district_id = ? ', array($district))->get();


        foreach ($lists as $k => $v) {
            $id = $v->id;
            $data = $v->name;
            $options.= '<option value="' . $id . '">' . $data . '</option>';
        }

        return $options;
    }

}


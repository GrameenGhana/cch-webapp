<?php

class DistrictController extends \BaseController {

    public function __construct() {
        $this->regions = array();
        $region = District::groupBy("region")->get();
        $this->districtIds = User::getUserDistricts(Auth::user()->id);

            $this->isDistrictAdmin =false;

        if (strtolower(Auth::user()->role) == 'district admin' || strpos(strtolower(Auth::user()->role), "supervisor") != '') {

            $this->isDistrictAdmin = true;

            $districts = District::whereRaw(' id  in (?) ', array($this->districtIds))->get();
            $region = User::getUserRegions(Auth::user()->id);
        } else {
            $districts = District::all();

            $region = District::groupBy("region")->get();
        }


        foreach ($region as $key => $value) {
            $this->regions[$value->region] = $value->region;
        }
        $this->rules = array('name' => 'required|min:3', 'region' => 'required');
    }

//	private $regions;
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        if ($this->isDistrictAdmin) {
            $districts = District::whereRaw(' id  in (?) ', array($this->districtIds))->get();
        } else
            $districts = District::all();
        return View::make('districts.index', array('districts' => $districts));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return View::make('districts.create', array("region" => $this->regions));   //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $validator = Validator::make(Input::all(), $this->rules);



        if ($validator->fails()) {
            return Redirect::to('/district/create')
                            ->with('flash_error', 'true')
                            ->withErrors($validator);
        } else {
            $district = new District;
            $district->name = Input::get('name');

            $district->region = Input::get('region');
            $district->country = 'Ghana';
            $district->save();
            Session::flash('message', "{$district->name} created successfully");
            return Redirect::to('/districts');
        }
    }

    public function showPeople($id) {
        $districts = District::find($id);
        //  return View::make('districts.people', array('district' => $districts));
        $rawResult = (array) DB::select("select u.* from cch_users u inner join cch_facility_user cfu on u.id=cfu.user_id and cfu.primary=1  inner join cch_facilities cf on cfu.facility_id = cf.id where cf.district='$id'");
        $users = User::hydrate($rawResult);

        return View::make('districts.people', array('district' => $districts, 'users' => $users));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
//
        $district = District::find($id);
        return View::make('districts.edit', array("region" => $this->regions));   //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
//
        $district = District::find($id);
        return View::make('districts.edit', array('district' => $district, "region" => $this->regions));   //
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
            return Redirect::to('/district/' . $id . 'edit')
                            ->with('flash_error', 'true')
                            ->withErrors($validator);
        } else {
            $district = District::find($id);
            $district->name = Input::get('name');

            $district->region = Input::get('region');
            $district->country = 'Ghana';
            $district->save();
            Session::flash('message', "{$district->name} edited successfully");
            return Redirect::to('/districts');
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

//retrieve 
    public function getDistricts() {
//
        $region = Input::get('region');

        if (!$region) {
            return false;
        }

        $options = "";
        $districtlists = District::whereRaw('region = ? ', array($region))->get();


        foreach ($districtlists as $k => $v) {
            $id = $v->id;
            $data = $v->name;
            $options.= '<option value="' . $id . '">' . $data . '</option>';
        }

        return $options;
    }

}


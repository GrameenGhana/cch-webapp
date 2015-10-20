<?php

class FacilityTypeController extends \BaseController {

    public function __construct() {

        $this->rules = array('name' => 'required|min:3', 'code' => 'required');
    }

//	private $regions;
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $facilityType = FacilityType::all();
        return View::make('facilitytype.index', array('facilitytype' => $facilityType));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return View::make('facilitytype.create');   //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $validator = Validator::make(Input::all(), $this->rules);

        if ($validator->fails()) {
            return Redirect::to('/facilitytype/create')
                            ->with('flash_error', 'true')
                            ->withErrors($validator);
        } else {
            $facilityType = new FacilityType;
            $facilityType->name = Input::get('name');

            $facilityType->id = Input::get('code');
            $facilityType->save();
            Session::flash('message', "{$facilityType->name} created successfully");
            return Redirect::to('/facilitytype');
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
        $facilityType = FacilityType::find($id);
        return View::make('facilitytype.edit');   //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
//
        $facilityType = FacilityType::find($id);
        return View::make('facilitytype.edit', array("facilitytype" => $facilityType));   //
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
//            $facilityType = FacilityType::find($id);
            $facilityType = FacilityType::find($id);

            $facilityType->name = Input::get('name');

            $facilityType->save();
            Session::flash('message', "{$facilityType->name} edited successfully");
            return Redirect::to('/facilitytype');
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


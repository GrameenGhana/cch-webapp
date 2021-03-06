<?php

class FacilityController extends BaseController {

    private $districts;
    private $rules;
    public $facilityTypes;

    public function __construct() {
        $this->beforeFilter('auth');

        if (strtolower(Auth::user()->role) == 'district admin') {
            $facs = Facility::whereIn('id', function($query) {
                        $query->select('facility_id')
                                ->from(with(new FacilityUser)->getTable())
                                ->where('user_id', Auth::user()->id)
                                ->where('supervised', 1)
                                ->groupBy("district");
                    })->get();

            $this->districts = array();
            foreach ($facs as $facility) {
                $this->districts[$facility->region][$facility->district] = $facility->district;
            }
        } else {
            $this->districts = array(
                'Greater Accra' => array('Ada East' => 'Ada East', 'Ada West' => 'Ada West', 'Ningo Prampram' => 'Ningo Prampram', 'Accra Metro' => 'Accra Metro'),
                'Volta' => array('South Dayi' => 'South Dayi', 'South Tongu' => 'South Tongu', 'Ho Municipal' => 'Ho Municipal','KRACHI WEST'=>'KRACHI WEST',
					'KRACHI NCHUMURU'=>'KRACHI NCHUMURU',
					'KRACHI EAST'=>'KRACHI EAST',
					'NKWANTA SOUTH'=>'NKWANTA SOUTH',
					'NKWANTA NORTH'=>'NKWANTA NORTH',
					'KADJEBI'=>'KADJEBI',
					'JASIKAN'=>'JASIKAN',
					'BIAKOYE'=>'BIAKOYE',
					'HOHOE'=>'HOHOE',
					'KPANDO'=>'KPANDO',
					'AFADZATO'=>'AFADZATO',
					'NORTH DAYI'=>'NORTH DAYI',
					'HO WEST'=>'HO WEST',
					'HO CENTRAL'=>'HO CENTRAL',
					'ADAKLU'=>'ADAKLU',
					'AGORTIME ZIOPE'=>'AGORTIME ZIOPE',
					'AKATSI NORTH'=>'AKATSI NORTH',
					'NORTH TONGU'=>'NORTH TONGU',
					'CENTRAL TONGU'=>'CENTRAL TONGU',
					'AKATSI SOUTH'=>'AKATSI SOUTH',
					'KETU NORTH'=>'KETU NORTH',
					'KETU SOUTH'=>'KETU SOUTH',
					'KETA'=>'KETA'));
					

            $facs = Facility::all();

            $this->districts = array();
            foreach ($facs as $facility) {
                $this->districts[$facility->region][$facility->district] = $facility->district;
            }

            $this->districts = array();
            $dists = District::all();
            foreach ($dists as $key => $value) {
                $this->districts[$value->region][$value->id] = $value->name;
            }
        }

        $this->subdistricts = array();
        $this->subdistricts["Unknown"][""]="Unknown";
        $dists = SubDistrict::all();
        foreach ($dists as $key => $value) {
               $this->subdistricts[$value->district->name][$value->id] = $value->name;
        }
        

        $this->facilityTypes = array();
        $facTypes = FacilityType::all();
        foreach ($facTypes as $key => $value) {
            $this->facilityTypes[$value->id]=$value->name;
        }

        $this->rules = array('name' => 'required|min:3', 'district' => 'required');
    }

    public function showPeople($fid) 
    {
        $fac = Facility::find($fid);
        return View::make('facilities.people', array('facility' => $fac));
    }

    public function showCalendar($id) 
    {
        $facility = Facility::find($id);
        $events = array();

        foreach ($facility->users as $u) {
            $logs = $u->tracklogs()->where('module', '=', 'Calendar')->get();
            foreach ($logs as $log) {
                $e = $this->createEventForJS($log->data, $log->start_time, $log->end_time);
                if ($e != null) {
                    $s = serialize($e);
                    $events[$s] = $e;
                }
            }
            return View::make('facilities.calendar', array('facility' => $facility, 'events' => $events));
        }
    }

    public function showDistrictCalendar($id, $district) 
    {
        $user = User::find($id);
        $events = array();
        $legend = array();

        foreach ($user->facilities as $facility) {
            if ($facility->district == $district) {
                $bg = '#' . $this->random_color();
                $legend[$facility->name] = $bg;

                foreach ($facility->users as $u) {
                    $logs = $u->tracklogs()->where('module', '=', 'Calendar')->get();
                    foreach ($logs as $log) {
                        $e = $this->createEventForJS($log->data, $log->start_time, $log->end_time, $bg, $bg);
                        if ($e != null) {
                            $s = serialize($e);
                            $events[$s] = $e;
                        }
                    }
                    break;
                }
            }
        }
        return View::make('facilities.districtcalendar', array('district' => $district, 'events' => $events, 'legend' => $legend));
    }

    public function index() 
    {
        if (strtolower((Auth::user()->role) == 'district admin' || 
            strpos(strtolower(Auth::user()->role),"supervisor")>=0) && 
            strtolower(Auth::user()->role)!="admin"  ) {
            echo Auth::user()->role." Admin";


            $facs = Facility::whereIn('id', function($query) {
                        $query->select('facility_id')
                                ->from(with(new FacilityUser)->getTable())
                                ->where('user_id', Auth::user()->id)
                                ->where('supervised', 1);
                    })->get()
            ;
        } else {
            $facs = Facility::all();
            echo Auth::user()->role." Admins";
        } 
        
        return View::make('facilities.index', array('facilities' => $facs));
    }

    public function show($id) {
        $facility = Facility::find($id);
        return View::make('facilities.show', array('facility' => $facility));
    }

    public function create() {
        return View::make('facilities.create', array('districts' => $this->districts,'facilityTypes'=>$this->facilityTypes,'subdistricts'=>$this->subdistricts));
    }

    public function store() {
        $validator = Validator::make(Input::all(), $this->rules);

        $validator->sometimes('motechid', 'numeric|digits:5', function($input) {
            return $input->motechid <> '';
        });


        if ($validator->fails()) {
            return Redirect::to('/facilities/create')
                            ->with('flash_error', 'true')
                            ->withErrors($validator);
        } else {
            $facility = new Facility;
            $facility->name = Input::get('name');
            $facility->district = Input::get('district');
            $facility->sub_district = Input::get('sub_district');
            $facility->facility_type = Input::get('fac_type');
            $facility->motech_facility_id = Input::get('motechid');
            $facility->region = $this->getRegionFromDistrict(Input::get('district'));
            $facility->created_at = date('Y-m-d h:m:s');
            $facility->modified_by = Auth::user()->id;

            // using default values - consider changing
            $facility->country = 'Ghana';

            $facility->save();
            //print '<pre>'; print_r($facility); print '</pre>'; exit;

            Session::flash('message', "{$facility->name} created successfully");
            return Redirect::to('/facilities');
        }
    }

    public function edit($id) {
        $facility = Facility::find($id);
        return View::make('facilities.edit', array('facility' => $facility, 'districts' => $this->districts,'facilityTypes'=>  $this->facilityTypes,'subdistricts'=>$this->subdistricts));
    }

    public function update($id) 
    {
        $validator = Validator::make(Input::all(), $this->rules);

        $validator->sometimes('motechid', 'numeric|digits:5', function($input) {
            return $input->motechid <> '';
        });

        if ($validator->fails()) {
            return Redirect::to('facilities/' . $id . '/edit')
                            ->with('flash_error', 'true')
                            ->withInput()
                            ->withErrors($validator);
        } else {
            $facility = Facility::find($id);
            $facility->name = Input::get('name');
            $facility->district = Input::get('district');
            $facility->sub_district = Input::get('sub_district');
            $facility->motech_facility_id = Input::get('motechid');
            $facility->region = $this->getRegionFromDistrict(Input::get('district'));
            $facility->facility_type = Input::get('fac_type');
            $facility->comment = Input::get('comment');
            $facility->modified_by = Auth::user()->id;
            $facility->save();

            Session::flash('message', "{$facility->name} updated successfully");
            return Redirect::to('facilities');
        }
    }

    public function destroy() 
    {
        $facs = Facility::all();
        return View::make('facilities.index', array('facilities' => $facs));
    }

    private function getRegionFromDistrict($district) 
    {
        foreach ($this->districts as $region => $districts) {
            foreach ($districts as $d) {
                if ($d == $district)
                    return $region;
            }
        }
        return 'unknown';
    }

    public function getFacilities() 
    {
        $district = Input::get( 'district' );
        
        if(!$district) { return false; }

        $options="";
        $lists = Facility::whereRaw('district= ? ',array($district) )->get();
        
        foreach ($lists as $k => $v) {
            $id=$v->id;
            $data=$v->name;
            $options.= '<option value="'.$id.'">'.$data.'</option>';

        }

        return $options;
    }

}


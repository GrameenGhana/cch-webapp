
<?php

class SupervisorController extends BaseController {

	public function index()
	{
       $data = array();

       $supervisors = User::Supervisor()->get();

       foreach($supervisors as $sup)
       {
            $s = $this->details($sup); 
            array_push($data,array('supervisor'=>$s));
       }

       return Response::json(array('error' => false, 'data' => $data), 200);
	}

    public function getData($id, $resource, $time)
    {
        $ts = $this->microtime_float();
        $error = false;
        $data = array();
        $time = (preg_match('/^\d+$/',$time)) ? $time : 1325376000; 
        $limit = date('Y-m-d i:m:s', strtotime(date('r', $time)));

        $sup = User::whereRaw('username=? and role like "%Supervisor%"',array($id))->first();

        if (is_null($sup)) {
            $error = true;
            $data['message'] = "Supervisor $id not found";
        } else {
            switch(strtolower($resource)) {
                case 'location':
                    $data = $this->location_details($sup, $time);
                    break;
                case 'nurse':
                    $data = $this->nurse_details($sup, $time);
                    break;
                case 'course':
                    $data = $this->course_details($sup, $time);
                    break;
                case 'event':
                    $data = $this->event_details($sup, $time);
                    break;
                case 'target':
                    $data = $this->target_details($sup, $time);
                    break;
                default:
                    $data = $this->details($sup);
            }
        }

        $te = $this->microtime_float();
        $tt = (($te-$ts)/1000).'s';

    	return Response::json(array('error' => $error, 
                                    'version'=>$time, 
                                    'time_taken'=>$tt, 
                                    'data' => $data), 200);
    }


    public function show($id)
    {
        $ts = $this->microtime_float();

        $sup = User::whereRaw('username=? and role like "%Supervisor%"',array($id))->first();

        if (is_null($sup)) {
		    $errors = array('Supervisor not found'); 
            $te = $this->microtime_float();
            Log::info("Time for $id: " . ($te-$ts)."s");
    		return Response::json(array('error' => true, 'messages'=>$errors), 200);
        } else {
            $s = $this->details($sup); 
            $te = $this->microtime_float();
            Log::info("Time for $id: " . ($te-$ts)."s");
    	    return Response::json(array('error' => false, 'data' => array('supervisor'=>$s)), 200);
        }	    
    }
    
    public function store()
    {
        $data = Input::get('data',0);
        $data = json_decode($data);
        $id = $data->info[0]->id;
        return $this->show($id);
    }

    protected function location_details($sup, $time)
    {
        $regions = array('new'=> array(), 'updated'=>array(), 'deleted'=>array());
        $districts = array('new'=> array(), 'updated'=>array(), 'deleted'=>array());
        $facs = array('new'=> array(), 'updated'=>array(), 'deleted'=>array());
        $seen = array();

        foreach($sup->facilities()->withTrashed()->get() as $k=>$v)
        {
                # process facilities
                if (!in_array($v->name, $seen)) {
                    $idx = $this->getIndex($v->created_at, $v->updated_at, $v->deleted_at, $time);
                    if ($idx != 'unchanged'){
                        $ftype = (strpos($v->name, 'CHPS') !== false) ? 'CHPS' : 'HC'; 
                        if ($idx=='deleted') { array_push($facs[$idx], $v->id); } else {
                        array_push($facs[$idx], array('id'=> $v->id,
                                            'name'=> $v->name,
                                            'type'=> $ftype,
                                            'did'=> $v->facDistrict->id,
                                            'district'=> $v->facDistrict->name,
                                            'region'=> $v->facDistrict->region,
                                            'created_at'=>$v->created_at,
                                            'updated_at'=>$v->updated_at,
                                            'deleted_at'=>$v->deleted_at));
                   }
                        array_push($seen, $v->name);
                   }
                }

                # process districts
                if (!in_array($v->facDistrict->name, $seen)) {
                    $idx = $this->getIndex($v->facDistrict->created_at, $v->facDistrict->updated_at, 
                                       $v->facDistrict->deleted_at, $time);
                    if ($idx != 'unchanged') {
                        if ($idx=='deleted') { array_push($districts[$idx], $v->facDistrict->id); } else {
                        array_push($districts[$idx], array('id'=> $v->facDistrict->id,
                                                 'name'=> $v->facDistrict->name,
                                                 'region'=> $v->facDistrict->region,
                                                 'created_at'=>$v->facDistrict->created_at,
                                                 'updated_at'=>$v->facDistrict->updated_at,
                                                 'deleted_at'=>$v->facDistrict->deleted_at));
                                                 }
                        array_push($seen, $v->facDistrict->name);
                    }
                }

                # process regions
                if (!in_array($v->facDistrict->region, $seen)) {
                    $r = Region::where('name','=',$v->facDistrict->region)->first();
                    $idx = $this->getIndex($r->created_at, $r->updated_at, $r->deleted_at, $time);
                    if($idx!='unchanged') {
                        if ($idx=='deleted') { array_push($regions[$idx], $r->id); } else {
                        array_push($regions[$idx], array('id'=>$r->id,
                                                         'name'=> $r->name,
                                            'created_at'=>$r->created_at,
                                            'updated_at'=>$r->updated_at,
                                            'deleted_at'=>$r->deleted_at));
                         }
                        array_push($seen, $v->facDistrict->region);
                    }
                }   
        }

        $s = array('name'=>$sup->getName(),
                   'username'=>$sup->username,
			       'role'=>$sup->role,
                   'regions'=>$regions,
                   'districts'=>$districts,
                   'facilities'=>$facs);

        return $s;
    }

    protected function nurse_details($sup, $time)
    {
        $nurses = array('new'=> array(), 'updated'=>array(), 'deleted'=>array());
        $seen = array();

        foreach($sup->facilities as $k=>$v)
        {
                foreach($v->nurses(false) as $n)
                {
                    $key = $n->id.'-'.$n->first_name . ' ' . $n->last_name;
                    
                    if (!in_array($key, $seen)) {
                        $idx = $this->getIndex($n->created_at, $n->updated_at, $n->deleted_at, $time);

                        $good = array('ACTIVE','OWNDEVICE');

                        if ($idx != 'unchanged' && 
                            (in_array($n->status,$good) || ($sup->status=='TEST' && $n->status=='TEST'))) {     
                        
                             if ($idx=='deleted') { array_push($nurses[$idx], $n->id); } else {
                                    array_push($nurses[$idx], array('id'=> $n->username,
                                                      'name'=> $n->first_name . ' ' . $n->last_name,
                                                      'title'=> $n->title,
                                                      'role'=>$n->role,
                                                      'is_chn'=>$n->ischn,
                                                      'group'=>$n->group,
                                                      'status'=>$n->status,
                                                      'gender'=>$n->gender,
                                                      'gender'=>$n->gender,
                                                      'phone_number'=>$n->phone_number,
                                                      'my_facility'=>$n->myfac,
                                                      'fid'=> $v->id,
                                                      'facility'=> $v->name,
                                                      'did'=> $v->facDistrict->id,
                                                      'district'=> $v->facDistrict->name,
                                                      'region'=> $v->facDistrict->region,
                                                      'created_at'=>$n->created_at,
                                                      'updated_at'=>$n->updated_at,
                                                      'deleted_at'=>$n->deleted_at));
                                    }
                                    array_push($seen, $key);
                          }
                    }
                }
        }

        $s = array('name'=>$sup->getName(),
                   'username'=>$sup->username,
                   'nurses'=>$nurses);

        return $s;
    }

    protected function course_details($sup, $time)
    {
        $courses = array('new'=> array(), 'updated'=>array(), 'deleted'=>array());
        $seen = array();

        foreach($sup->facilities as $k => $v)
        {
                foreach($v->nurses(false) as $n)
                {
                    $key = $n->id.'-'.$n->first_name . ' ' . $n->last_name;
                    
                    if (!in_array($key, $seen)) {
                        $nidx = $this->getIndex($n->created_at, $n->updated_at, $n->deleted_at, $time);
                        $idx = ($nidx=='deleted') ? 'deleted' : 'new';

                        foreach($n->courses() as $j => $e){
                                unset($e['topics']);
                                $e['title'] = $j;
                                $e['nurseId']= $n->username;
                                $e['fid']= $v->id;
                                $e['facility']= $v->name;
                                $e['did']= $v->facDistrict->id;
                                $e['district']= $v->facDistrict->name;
                                $e['region']= $v->facDistrict->region;
                                array_push($courses[$idx], $e);
                        }
                    }
                }
        }

        $s = array('name'=>$sup->getName(),
                   'username'=>$sup->username,
                   'courses'=>$courses);

        return $s;
    }

    protected function event_details($sup, $time)
    {
        $events = array('new'=> array(), 'updated'=>array(), 'deleted'=>array());
        $seen = array();

        foreach($sup->facilities as $k => $v)
        {
                foreach($v->nurses(false) as $n)
                {
                    $key = $n->id.'-'.$n->first_name . ' ' . $n->last_name;
                    
                    if (!in_array($key, $seen)) {
                        $nidx = $this->getIndex($n->created_at, $n->updated_at, $n->deleted_at, $time);
                        $idx = ($nidx=='deleted') ? 'deleted' : 'new';

                        foreach($n->events(true) as $j => $e){
                            if ($e->deleted_at<>'' && $e->deleted_at<>'0000-00-00 00:00:00') {
                                array_push($events['deleted'], $e->id);
                            } else {
                                $e->nurse = $n->first_name. ' '.$n->last_name;
                                $e->fid= $v->id;
                                $e->facility= $v->name;
                                $e->did= $v->facDistrict->id;
                                $e->district= $v->facDistrict->name;
                                $e->region= $v->facDistrict->region;
                                array_push($events[$idx], $e);
                            }
                        }
                    }
                }
        }

        $s = array('name'=>$sup->getName(),
                   'username'=>$sup->username,
                   'events'=>$events);

        return $s;
    }

    protected function target_details($sup, $time)
    {
        $targets = array('new'=> array(), 'updated'=>array(), 'deleted'=>array());
        $seen = array();

        foreach($sup->facilities as $k => $v)
        {
                foreach($v->nurses(false) as $n)
                {
                    $key = $n->id.'-'.$n->first_name . ' ' . $n->last_name;
                    
                    if (!in_array($key, $seen)) {
                        $nidx = $this->getIndex($n->created_at, $n->updated_at, $n->deleted_at, $time);
                        $idx = ($nidx=='deleted') ? 'deleted' : 'new';

                        foreach($n->targets(true) as $j => $e){
                                $e->fid= $v->id;
                                $e->facility= $v->name;
                                $e->did= $v->facDistrict->id;
                                $e->district= $v->facDistrict->name;
                                $e->region= $v->facDistrict->region;
                                array_push($targets[$idx], $e);
                        }
                    }
                }
        }

        $s = array('name'=>$sup->getName(),
                   'username'=>$sup->username,
                   'targets'=>$targets);

        return $s;
    }

    protected function details($sup)
    {
        $x = date('U',strtotime('1970-01-01'));

        $data = $this->location_details($sup, $x);
        $regions = $data['regions'];
        $districts = $data['districts'];
        $facs = $data['facilities'];

        $data = $this->nurse_details($sup,$x);
        $nurses = $data['nurses'];

        $data = $this->course_details($sup,$x);
        $courses = $data['courses'];

        $data = $this->event_details($sup,$x);
        $events = $data['events'];

        $data = $this->target_details($sup,$x);
        $targets = $data['targets'];

        $s = array('name'=>$sup->getName(),
                   'username'=>$sup->username,
			       'role'=>$sup->role,
                   'regions'=>$regions,
                   'districts'=>$districts,
                   'facilities'=>$facs,
                   'nurses'=>$nurses,
                   'courses'=>$courses,
                   'events'=>$events,
                   'targets'=>$targets);

        return $s;
    }

    private function getIndex($c_at,$u_at,$d_at,$time)
    {
        $idx = '';
        if (($d_at!=null) && $this->dateAfter($d_at, $time)) { $idx= 'deleted'; } 
        else if ($this->dateAfter($c_at, $time)) { $idx= 'new'; } 
        else if ($this->dateAfter($u_at, $time)) { $idx= 'updated'; } 
        else { $idx = 'unchanged'; }
        return $idx;
    }

    private function dateAfter($date1, $date2)
    {
         return (strtotime($date1) >= strtotime(date('r',$date2)));
    }

    private function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
            return ((float)$usec + (float)$sec);
    }
}

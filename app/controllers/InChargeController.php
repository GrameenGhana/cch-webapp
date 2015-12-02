
<?php

class InChargeController extends BaseController {

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

    public function show($id)
    {
//        $sup = User::whereRaw('id=? and role="Supervisor"',array($id))->first();
     $sup = User::whereRaw('username=? and role like "%Supervisor%"',array($id))->first();

        if (is_null($sup)) {
		    $errors = array('Supervisor not found'); 
    		return Response::json(array('error' => true, 'messages'=>$errors), 200);
        } else {
            $s = $this->details($sup); 
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


    protected function details($sup)
    {
            $facs = array();
            foreach($sup->facilities as $k=>$v)
            {
                $nurses = array();
                foreach($v->nurses() as $n)
                      {
if($n->status=='ACTIVE' || $sup->status=='TEST' && $n->status=='TEST') {     
         array_push($nurses, $n->toArray());
                }}
         array_push($facs, array('name'=>$v->name,
                                 'id'=>$v->id,
                                 'district'=>$v->facDistrict->name,
                                 'did'=>$v->facDistrict->id,
                                
                                 'region'=>$v->facDistrict->region,

                                'nurses'=>$nurses,
                                  ));
          }

            $s = array('name'=>$sup->getName(),
                       'username'=>$sup->username,
			'role'=>$sup->role,
                       'facilities'=> $facs);

            return $s;
    }


    public function getSupData($username){
        //$username = Input::get('username');

        $sup = User::whereRaw('username=? and role like "%Supervisor%"',array($username))->first();

        if (is_null($sup)) {
            $errors = array('Supervisor not found');
            return Response::json(array('error' => true, 'messages'=>$errors), 200);
        } else {

            $facs = array();
            foreach($sup->facilities as $k=>$v)
            {
                $nurses = array();

                $seen = array();

                foreach ($v->users as $l => $u) {

                    if ((!in_array($u->id, $seen)) ) {

                        $u->myfac = $v->name;

                        $u->calendar = $this->getUserEvents($u->id);
                        $u->courses = $this->getUserCourses($u->id);
                        $u->targets = $this->getUserTargets($u->id);
                        array_push($nurses, $u->toArray());
                        array_push($seen, $u->id);
                    }
                }


                array_push($facs, array('name'=>$v->name,
                    'id'=>$v->id,
                    'district'=>$v->facDistrict->name,
                    'did'=>$v->facDistrict->id,

                    'region'=>$v->facDistrict->region,

                    'nurses'=>$nurses,
                ));
            }

            $s = array('name'=>$sup->getName(),
                'username'=>$sup->username,
                'role'=>$sup->role,
                'facilities'=> $facs);


            return Response::json(array('error' => false, 'data' => array('supervisor'=>$s)), 200);
        }

    }

    protected function getUserEvents($username){
        $eventsdata = DB::table('user_events')
            ->where('username','=',$username)
            ->get();

        Log::info("Count of Events" . count($eventsdata));

        $events = array();

        foreach($eventsdata as $e => $event) {

            $location = (isset($event->location)) ? $event->location : 'unknown location.';
            $justification = (isset($event->justification)) ? $event->justification : 'no justification.';
            $comments = (isset($event->comments)) ? $event->comments : 'no comments.';
            $status = (isset($event->status)) ? $event->status : 'unknown';

            $s = md5( $event->eventid);

            $events[$s] = array('title' => addslashes(trim(@$event->eventtype . ' at ' . $location)),
                'location' => addslashes($location),
                'type' => addslashes(@$event->eventtype),
                'start' => $event->start,
                'end' => $event->end,
                'eventid' => $event->eventid,
                'justification' => $justification,
                'comments' => $comments,
                'status' => $status);


        }



        return $events;
    }

    protected function getUserTargets($username){
        $targetsdata = DB::table('user_targets')
            ->where('username','=',$username)
            ->get();


        $targets= array();

        foreach($targetsdata as $t => $target) {

            $s = md5($target->category . $target->targetid);

            $targets[$s] = array(
                'id' => $target->targetid, 'category' => $target->category,
                'type' => $target->type,
                'target' => $target->target,
                'achieved' => $target->achieved,
                'justification' => ($target->justification),
                'start' => $target->start,
                'end' => $target->end);


        }

        return $targets;
    }

    protected function getUserCoursesTopics($course){
        $topicsdata = DB::table('user_courses_topics')
            ->where('course','=',$course)
            ->get();


        $topics= array();

        foreach($topicsdata as $t => $topic) {


            $topics[$topic->title] = array(
                'last_accessed' => $topic->last_accessed,
                'time_taken' => $topic->time_taken,
                'percentcomplete' => $topic->percentcomplete,
                'activities' => $topic->activities);


        }

        return $topics;
    }

    protected function getUserCourses($username){
        $coursesdata = DB::table('user_courses')
            ->where('username','=',$username)
            ->get();


        $courses= array();

        foreach($coursesdata as $c => $course) {


            $courses[$course->title] = array(
              //  'topics' => $this->getUserCoursesTopics($course->title),
                'attempts' => $course->attempts,
                'score' => $course->score,
                'time_taken' => $course->time_taken,
                'last_accessed' => $course->last_accessed,
                'percentcomplete' => $course->percentcomplete);


        }

        return $courses;
    }


public function showdetail($id) {
//        $sup = User::whereRaw('id=? and role="Supervisor"',array($id))->first();
        $sup = User::whereRaw('username=?', array($id))->first();
        $surveys=DB::table('cch_tracker')
                    ->select(DB::raw('data,start_time'))
                    ->where('username','=',$id)
                    ->where('data','like','%profile%')
                    ->where('data','not like','%activity%')
                    ->get();

        $survey_popup=DB::table('cch_tracker')
                       ->select(DB::raw('data'))
                       ->where('username','=',$id)
                       ->where('module','like','%Survey%')
                       ->get();
        if (is_null($sup)) {
            $errors = array('User Not Found');
            return Response::json(array('error' => true, 'messages' => $errors), 200);
        } else {
            $s = $this->details($sup);
            $facilities = array();
            foreach ($sup->supervisedFacilities as $k => $v) {
                $facilities[] = array("id" => $v->id, "name" => $v->name);
            }


            $primary = array("id" =>@ $sup->getPrimaryFacility()->facility_id, "name" => $sup->getPrimaryFacilityDetails());

//            echo 'select * from cch.districts u where u.id =(select district from  cch.cch_facilities cf where cf.id='.$sup->getPrimaryFacility()->facility_id.' ) ';

              $district = DB::select('select * from cch.districts u where u.id =(select district from  cch.cch_facilities cf where cf.id=? ) ', array(@ $sup->getPrimaryFacility()->facility_id));
             $dist = "No District";
	     foreach($district as $d)
		{$dist = $d->name;
        
}
         //   $groups=DB::select('SELECT cu.username, cu.last_name,cu.first_name,
           //                      IF(cu.ischn = 1, "Yes",IF(cu.ischn  = 0 ,"No","No"))
             //                    as is_chn,cf.facility_type ,cf.name,cu.status 
               //                  FROM cch.cch_users cu 
                 //                LEFT JOIN cch.cch_facility_user cfu  
                   //              ON cu.id = cfu.user_id 
                     //            AND cfu.`primary` = 1 
                       //          LEFT JOIN cch.cch_facilities cf on cf.id = cfu.facility_id 
                         //        LEFT JOIN cch.districts d on d.id=cf.district  
                           //      AND d.id='.$d->id.' where cu.status="ACTIVE" group by cu.username');

            return Response::json(array('error' => false,"user_id"=>$sup->id, 'last_name' => $sup->last_name, "first_name" => $sup->first_name,
                        "role" => $sup->role, "ischn" => $sup->ischn, "title" => $sup->title,
                        "primary_facility" => $primary,
                        "district" => $dist,
                       // "groups"=>$groups,
                        "supervised_facility" => $facilities,
                        "survey_data"=>$surveys,
                        "survey_popup"=>$survey_popup));

        }
    }
    public function achievements($id){
         $target_achievements=DB::connection('mysql2')
                             ->select(DB::raw('Select t.* from targetsetting t left outer join targetsetting t2 on(t.target_id=t2.target_id and t.nurseid=t2.nurseid and t.startdate<t2.startdate)  where t2.id is null and t.nurseid=:id'),array('id'=>$id,));
                                 $course_achievements=DB::connection('mysql2')
                                ->table('learningcenter_quizzes')
                                ->select(DB::raw('*'))
                                ->where('username','=',$id)
                                ->get();

                              return Response::json(array("targets"=>$target_achievements,
                                                            "courses"=>$course_achievements));
    }
}


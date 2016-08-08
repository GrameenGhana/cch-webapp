<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Venturecraft\Revisionable\Revisionable;


class User extends Revisionable implements UserInterface, RemindableInterface {

    protected $table = 'cch_users';
    protected $dates = ['deleted_at'];

    protected $hidden = array('password', 'remember_token');

    protected $revisionEnabled = true;
    protected $revisionCleanup = true; //Remove old revisions (works only when used with $historyLimit)
    protected $historyLimit = 500;
    protected $revisionCreationsEnabled = true;
    protected $revisionNullString = 'nothing';
    protected $revisionUnknownString = 'unknown';

    protected $revisionFormattedFields = array(
        'online' => 'boolean:Logged Out|Logged In',
    );

    protected $dontKeepRevisionOf = array( 'remember_token','password');

    public function identifiableName()
    {
        return $this->first_name.' '.$this->last_name;
    }


    public function modifier() {
        return $this->hasOne('User', 'id', 'modified_by');
    }

    public function facilities() {
        return $this->belongsToMany('Facility', 'cch_facility_user');
    }

    public function supervisedFacilities() {
        return $this->belongsToMany('Facility', 'cch_facility_user')->where("supervised", 1);
    }

    public function device() {
        return $this->hasOne('Device', 'user_id', 'id');
    }   

    public function zone() {
        return $this->hasOne('Zone', 'zone_id', 'id');
    }

    public function tracklogs() {
        return $this->hasMany('Tracker', 'username', 'username');
    }

    public static function getByUsername($username)
    {
        return User::whereRaw('username="'.$username.'"')->first();
    }

    public function getFacilitiesByTime($time) {

    }

    function getPrimaryFacilityDetails() {
        return ($this->getPrimaryFacilityId() == 0) ? "No Facility" : Facility::find($this->getPrimaryFacilityId())->name;
    }

    public function facilityList() {
        $list = "";
        $id = $this->getPrimaryFacilityId();
        foreach ($this->supervisedFacilities as $f) {
            $list .= $f->name . ' in ' . $f->facDistrict->name . '<br/>';
        }
        return $list;
    }

    public function getPrimaryFacility() {
        $facs = DB::select('select * from cch.cch_facility_user u where u.user_id = ? and u.primary =? ', array($this->id, 1));
        foreach ($facs as $f) {

            return $f;
        }
        return 0;
    }

    public function getPrimaryFacilityId() {
        $facs = DB::select('select * from cch.cch_facility_user u where u.user_id = ? and u.primary =? ', array($this->id, 1));
        foreach ($facs as $f) {
            return $f->facility_id;
        }
        return 0;
    }

    public  static function getUserRegions($id) {
        $facs = DB::select('select distinct cf.region from cch.cch_facilities cf inner join  cch.cch_facility_user cfu   on cf.id = cfu.facility_id  where cfu.user_id=? ', array($id));
       return $facs;
    }

    public  static function getUserSubDistricts($id) {
        $facs = DB::select('select distinct cf.sub_district from cch.cch_facilities cf inner join  cch.cch_facility_user cfu   on cf.id = cfu.facility_id  where cfu.user_id=? ', array($id));
        $dis = "";
        $cnt = 0;
        foreach ($facs as $f) {

            if ($cnt > 0)
                $dis.=",";
            $dis.=$f->district;
            $cnt++;
        }
        return $dis;
    }

    public  static function getUserDistricts($id) {
        $user = User::find($id);

        if (in_array($user->role, array('Admin'))) {
            $facs = DB::select('select distinct district from cch.cch_facilities');
        } else {
            $facs = DB::select('select distinct cf.district from cch.cch_facilities cf inner join  cch.cch_facility_user cfu on cf.id = cfu.facility_id  where cfu.user_id=? ', array($id));
        }

        $dis = "";
        $cnt = 0;
        foreach ($facs as $f) {
            if ($cnt > 0)
                $dis.=",";
            $dis.=$f->district;
            $cnt++;
        }
        return $dis;
    }

    public static function usersByDistrict() {
        $users = array('No Primary Facility' => array());
        if (strtolower(Auth::user()->role) == 'district admin') {
            $rawResult = (array) DB::select("SELECT cu.* FROM `cch_users` cu inner join cch_facility_user cfu    on cu.id =  cfu.user_id WHERE cfu.primary=1 and cfu.facility_id in (select facility_id from cch_facility_user where supervised=1  and  user_id=?) order by cu.first_name,cu.last_name ", array(Auth::user()->id));
            $usrs = User::hydrate($rawResult);
            foreach ($usrs as $user) {
                $fac = Facility::find($user->getPrimaryFacility()->facility_id);
                $user->primary_facility = $fac->id;
                $users[$fac->name][$user->id] = $user->username." - ".$user->getName();
            }
        } else {
            $usrs = User::with('facilities')->orderBy('first_name')->orderBy('last_name')->get();

            foreach ($usrs as $user) {
                $user->primary_facility = $user->getPrimaryFacilityId();
                foreach ($user->facilities as $f) {
                    if ($f->id == $user->primary_facility) {
                        if (in_array($f->facDistrict->name, array_keys($users))) {
                            $users[$f->facDistrict->name][$user->id] = $user->username." - ".$user->getName();
                        } else {
                            $users[$f->facDistrict->name] = array($user->id => $user->username." - ".$user->getName());
                        }
                    } elseif ($user->primary_facility == 0) {
                        $users['No Primary Facility'][$user->id] = $user->username." - ".$user->getName();
                    }
                }
            }
        }
        return $users;
    }

    public function districts() {
        $districts = array();

        foreach ($this->facilities as $k => $fac) {
            if (!in_array($fac->district, $districts)) {
                array_push($districts, $fac->district);
            }
        }

        return $districts;
    }

    public function courses() {
        $courses = array();

        $query = "SELECT t.user_id, t.course_id, c.title, max(tracker_date) as lasta, 
                         sum(t.time_taken) as time_taken, 
                         t.completed, t.activity_title, t.section_title, IFNULL(q.attempts,0) as attempts, 
                         IFNULL(q.percentscore,'Not taken') as score,
                         IFNULL(ksa.status,'In Progress') as ksa
                    FROM oppia_tracker t 
                    JOIN auth_user u ON u.id = t.user_id 
                    JOIN oppia_course c ON c.id = t.course_id 
                    LEFT JOIN jsi.finalquizscores q on q.course_id=t.course_id and q.user_id=t.user_id
                    LEFT JOIN cch_ksa_status ksa ON ksa.courseid = t.course_id and ksa.userid=t.user_id
                   WHERE t.course_id IS NOT null AND u.username = ? 
                   GROUP BY t.user_id, t.course_id, t.activity_title, t.section_title
                   ORDER BY t.course_id";

        $results = DB::select($query, array($this->username));

        $tt = array();
        $la = array();
        $comp = array();
        foreach ($results as $k => $v) {
            $v->title = trim(preg_replace('/{"en": "(.*?)"}/', '$1', $v->title));
            $v->activity_title = trim(preg_replace('/{"en": "(.*?)"}/', '$1', $v->activity_title));
            $v->section_title = trim(preg_replace('/{"en": "(.*?)"}/', '$1', $v->section_title));

            // Track time taken
            if (in_array($v->title, array_keys($tt))) {
                $tt[$v->title] += $v->time_taken;
            } else {
                $tt[$v->title] = $v->time_taken;
            }

            // Track last accessed date
            if (in_array($v->title, array_keys($la))) {
                if (strtotime($la[$v->title]) < strtotime($v->lasta)) {
                    $la[$v->title] = $v->lasta;
                }
            } else {
                $la[$v->title] = $v->lasta;
            }

            // Track percent complete
            if (in_array($v->title, array_keys($comp))) {
                $comp[$v->title]['numactivities'] += 1;
                $comp[$v->title]['numcomplete'] += ($v->completed) ? 1 : 0;
            } else {
                $comp[$v->title] = array('numactivities' => 1, 'numcomplete' => ($v->completed) ? 1 : 0);
            }

            $v->completed = ($v->completed == 1) ? 'Completed' : 'In progress';
            $origtt = $v->time_taken;
            $v->time_taken = $this->getHumanReadableTime($v->time_taken);

            if (in_array($v->title, array_keys($courses))) {
                if (in_array($v->section_title, array_keys($courses[$v->title]['topics']))) {
                    $courses[$v->title]['topics'][$v->section_title]['time_taken'] += $origtt;

                    if (strtotime($courses[$v->title]['topics'][$v->section_title]['last_accessed']) <
                            strtotime($v->lasta)) {
                        $courses[$v->title]['topics'][$v->section_title]['last_accessed'] = $v->lasta;
                    }
                    $courses[$v->title]['topics'][$v->section_title]['activities'] ++;
                    /* array_push($courses[$v->title]['topics'][$v->section_title]['activities'], 
                      array('activity'=>$v->activity_title,
                      'time'=>$v->time_taken,'done'=>$v->completed));
                     */
                    // update % complete
                    $numacts = $courses[$v->title]['topics'][$v->section_title]['activities'];
                    $currval = $courses[$v->title]['topics'][$v->section_title]['percentcomplete'];
                    $currcom = ($currval / 100) * ($numacts - 1);
                    $currcom += ($v->completed == 'Completed') ? 1 : 0;
                    $courses[$v->title]['topics'][$v->section_title]['percentcomplete'] = round(($currcom / $numacts * 100));
                } else {
                    $courses[$v->title]['topics'][$v->section_title] = array(
                        'last_accessed' => $v->lasta,
                        'time_taken' => $origtt,
                        'percentcomplete' => ($v->completed == 'Completed') ? 100 : 0,
                        'activities' => 1
                    );
                    /* array_push($courses[$v->title]['topics'][$v->section_title]['activities'], 
                      array('activity'=>$v->activity_title,
                      'time'=>$v->time_taken,'done'=>$v->completed));
                     */

                    $courses[$v->title]['score'] = ($v->score == 'Not taken') ? $v->score : round($v->score);
                    $courses[$v->title]['attempts'] = $v->attempts;
                }
            } else {
                $courses[$v->title] = array();
                $courses[$v->title]['topics'] = array();
                $courses[$v->title]['topics'][$v->section_title] = array(
                    'last_accessed' => $v->lasta,
                    'time_taken' => $origtt,
                    'percentcomplete' => ($v->completed == 'Completed') ? 100 : 0,
                    'activities' => 1,
                );

                $courses[$v->title]['cid'] = $v->course_id; 
                $courses[$v->title]['ksa'] = $v->ksa; 
                $courses[$v->title]['attempts'] = $v->attempts;
                $courses[$v->title]['score'] = ($v->score == 'Not taken') ? $v->score : round($v->score);
            }
        }

        foreach ($courses as $t => $topics) {
            $topics = $topics['topics'];
            foreach ($topics as $sect => $v) {
                $courses[$t]['topics'][$sect]['time_taken'] = $this->getHumanReadableTime($courses[$t]['topics'][$sect]['time_taken']);
            }
        }

        foreach ($tt as $k => $v) {
            $courses[$k]['time_taken'] = $this->getHumanReadableTime($v);
            $courses[$k]['last_accessed'] = date('M d, Y', strtotime($la[$k]));
            $courses[$k]['percentcomplete'] = round($comp[$k]['numcomplete'] / $comp[$k]['numactivities'] * 100);
            
            if ($courses[$k]['score'] !== 'Not Taken' && $courses[$k]['ksa'] != 'Passed') {
                if ($courses[$k]['score'] >= 85 && $courses[$k]['percentcomplete'] >= 85) {
                    $courses[$k]['ksa'] = 'Eligible';
                }
            }
        }

        return $courses;
    }

    private function getHumanReadableTime($secs) {
        $hours = floor(($secs * 60) / 3600);
        $mins = floor((($secs * 60) - ($hours * 3600)) / 60);
        $time = ($hours == 0) ? '' : $hours . 'h';
        $time.= ($mins == 0) ? '' : ' ' . $mins . 'm';
        $time = trim($time);
        return $time;
    }

    public function events($limit = false) {
        $events = array();

        $where = "nurseid='{$this->username}' ";
        if ($limit) {
            $f = ((new DateTime(date('Y-m-d')))->modify('first day of previous month')->format('Y-m-d'));
            $l = ((new DateTime(date('Y-m-d')))->modify('last day of next month')->format('Y-m-d'));
            $where .= "AND DATE(startdate) BETWEEN '$f' AND '$l'";
        }
 
        $sel = 'id, nurseid as nurseId, eventlocation as location,eventtype as type, startdate as start, ';
        $sel .= 'enddate as end, deleted_at, justification, comments, evstatus as status';
        $logs =  DB::connection('mysql2')->table('eventplanner')
                        ->select(DB::raw($sel))
                        ->whereRaw($where)
                        ->orderBy('startdate','Desc')
                        ->get();


        foreach ($logs as $log) {
                 $log->start =  date('U',strtotime($log->start));
                 $log->end =  date('U',strtotime($log->end));
                 $log->location = (isset($log->location)) ? $log->location : 'Unknown location';
                 $log->title = addslashes(trim(@$log->type . ' at ' . $log->location));
                 $log->justification = (!empty($log->justification))
                                     ? $log->justification : 'No Justification';
                 $log->comments = (!empty($log->comments)) ? $log->comments : 'No Comments';
                 $log->status = (!empty($log->status)) ? $log->status : 'Unknown';

                array_push($events, $log);
        }

        return $events;
    }

    public function targets($limit = true) {
        $targets = array();

        $where = "nurseid='{$this->username}' AND category NOT IN ('other') ";
        if ($limit) {
            $f = ((new DateTime(date('Y-m-d')))->modify('first day of previous month')->format('Y-m-d'));
            $l = ((new DateTime(date('Y-m-d')))->modify('last day of next month')->format('Y-m-d'));
            $where .= "AND DATE(startdate) BETWEEN '$f' AND '$l'";
        }

        $sel = 'id, nurseid as nurseId, target_number as target, target_type as type, category, achieved,'; 
        $sel .= 'justification, target_start_date as start, due_date as end, completed as status'; 
        $logs =  DB::connection('mysql2')->table('targetsetting')
                        ->select(DB::raw($sel))
                        ->whereRaw($where)
                        ->orderBy('start','Desc')
                        ->get();

        foreach ($logs as $log) {
                 $log->justification = (!empty($log->justification))
                                     ? $log->justification : 'No Justification';
                 $log->status = (!empty($log->status)) ? $log->status : 'Unknown';
                 array_push($targets, $log);
        }

        return $targets;
    }

    /*** old version based on logs table 
    public function events($limit = false) {
        $events = array();
        $deleted = array();

        $where = "nurseid='{$this->username}' ";
        if ($limit) {
            $f = ((new DateTime(date('Y-m-d')))->modify('first day of previous month')->format('Y-m-d'));
            $l = ((new DateTime(date('Y-m-d')))->modify('last day of next month')->format('Y-m-d'));
            $where .= "AND DATE(FROM_UNIXTIME(start_time /1000)) BETWEEN '$f' AND '$l'";
        }
 
        $logs = $this->tracklogs()->whereRaw($where)->orderBy('created_at',' desc')->limit(25)->get();

        foreach ($logs as $log) {
            $e = $this->createEvent($log->data, $log->start_time, $log->end_time);
            if ($e == null) {
                
            } else if (is_array($e)) {
                $s = md5($e["eventid"]);
                if (!array_key_exists($s, $deleted)) {
                    $events[$s] = $e;
                } else {
                    unset($events[$s]);
                }
            } else {
                $s = md5($e);
                $deleted[$s] = $e;
                unset($events[$s]);
            }
        }

        return $events;
    }

    public function targets($limit = true) {
        $targets = array();

        $f = ((new DateTime(date('Y-m-d')))->modify('first day of previous month')->format('Y-m-d'));
        $l = ((new DateTime(date('Y-m-d')))->modify('last day of next month')->format('Y-m-d'));

        $where = "module='Target Setting'";
        if ($limit) {
            //$where = ($limit) ? "module='Target Setting' AND DATE(FROM_UNIXTIME(start_time /1000)) BETWEEN '$f' AND '$l'" : "module='Target Setting'";
            $where .= ($limit) ? " AND DATE(FROM_UNIXTIME(start_time /1000)) BETWEEN '$f' AND '$l'";
        }
        
        $logs = $this->tracklogs()->whereRaw($where)->get();

        foreach ($logs as $log) {
            $e = $this->createTarget($log->data, $log->start_time, $log->end_time);
            if ($e != null) {
                $s = md5($e["category"] . $e["id"]);
                $targets[$s] = $e;
            }
        }

        return $targets;
    }
    */

    public function nurseCount() {
        $count = DB::table($this->table)
                ->select(DB::raw('count(*) as num'))
                ->whereRaw('role not in ("Supervisor","Admin","System","Researcher")')
                ->get();
        return $count[0]->num;
    }

    public function isNurse() {
        return (!in_array($this->role, array('Supervisor', 'Admin', 'system', 'Researcher')));
    }

    public function scopeNurses($query) {
        return $query->whereNotIn('roles', array('admin', 'system', 'Supervisor'));
    }

    public function scopeSupervisor($query) {
        return $query->where('role', '=', 'Supervisor');
    }

    public function getName() {
        return $this->first_name . ' ' . $this->last_name;
    }

    /** Authentication methods * */
    public function getAuthIdentifier() {
        return $this->getKey();
    }

    public function getAuthPassword() {
        return $this->password;
    }

    public function getRememberToken() {
        return $this->remember_token;
    }

    public function setRememberToken($value) {
        $this->remember_token = $value;
    }

    public function getRememberTokenName() {
        return 'remember_token';
    }

    public function getReminderEmail() {
        return $this->email;
    }


    public function createEvent($data, $start, $end) {
        $data = preg_replace("/\n/", "", $data);
        $events = json_decode($data);

        $eventid = (isset($events->eventid)) ? $events->eventid : '0';
        $category = (isset($events->category)) ? $events->category : '';

        $deleted=0;
        try {
            $deleted = @$events->deleted;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }

        if ($deleted == 0) {
            if (strtolower(@$events->eventtype) == 'personal' || strtolower($category) == 'personal') {
                return null;
            } else {
                $location = (isset($events->location)) ? $events->location : 'unknown location.';
                $justification = (isset($events->justification) && !empty($events->justification)>0) 
                                    ? $events->justification : 'No Justification';

                $comments = (isset($events->comments) && !empty($events->comments))
                                    ? $events->comments : 'No Comments';

                $status = 'Unknown' ;
                try {
                    if(strpos($data,'status') !== false && !empty($events->status)){ $status=$events->status; }
                } catch(Exception $ex){

                }
                
                $event = array(
                    'title' => addslashes(trim(@$events->eventtype . ' at ' . $location)),
                    'location' => addslashes($location),
                    'type' => addslashes(@$events->eventtype),
                    'start' => $start,
                    'end' => $end,
                    'eventid' => $eventid,
                    'justification' => $justification,
                    'comments' => $comments,
                    'status' => $status);
                return $event;
            }
        }
     
        return $eventid;
    }

    public function createTarget($data, $start, $end) {
        $data = preg_replace("/\n/", "", $data);
        $targets = json_decode($data);


        //   if ($targets->deleted == 0)
        if (true) {
            $category = "Other";
            try {
                $category = addslashes($targets->category);
            } catch (Exception $exc) {
                //echo $exc->getTraceAsString();
            }
            $type = "None";
            try {
                $type = addslashes($targets->target_type);
            } catch (Exception $exc) {
                //echo $exc->getTraceAsString();
            }


            $target = 0;
            try {
                $target = ($targets->target_number);
            } catch (Exception $exc) {
                //echo $exc->getTraceAsString();
            }

            $achieved_number = 0;
            try {
                $achieved_number = addslashes($targets->achieved_number);
            } catch (Exception $exc) {
                //echo $exc->getTraceAsString();
            }$start_date = "";
            try {
                $start_date = addslashes($targets->start_date);
            } catch (Exception $exc) {
                //echo $exc->getTraceAsString();
            }$due_date = "";
            try {
                $due_date = addslashes($targets->due_date);
            } catch (Exception $exc) {
                //echo $exc->getTraceAsString();
            }

            $justification = "";
            try {
                $justification = addslashes($targets->justification);
            } catch (Exception $exc) {
                //echo $exc->getTraceAsString();
            }

            $target_id = "";
            try {
                $target_id = addslashes($targets->id);
            } catch (Exception $exc) {
                //echo $exc->getTraceAsString();
            }

            $target = array(
                'id' => $target_id, 'category' => $category,
                'type' => $type,
                'target' => $target,
                'achieved' => $achieved_number,
                'justification' => ($justification),
                'start' => $start_date,
                'end' => $due_date);
            return $target;
        }

        return null;
    }

    public static function isDistrictAdmin($role) {
        if (in_array(strtolower($role), array('district admin','dhio','dhio assistant'))) {
            return true;
        }
        return false;
    }

    public static function getUsersInDistrict($id) {
        $rawResult = (array) DB::select("SELECT cu.* FROM cch_users cu inner join cch_facility_user cfu  on cu.id = cfu.user_id and cfu.primary=1 inner join  cch_facilities cf on cf.id = cfu.facility_id where cf.district =? and cu.status='active' order by cu.first_name asc, last_name asc ",array($id));
        $users = User::hydrate($rawResult);
        

        return $users;
    }

    /**
     * Set additional attributes as hidden on the current Model
     *
     * @return instanceof Model
     */
    public function addHidden($attribute) {
        $hidden = $this->getHidden();

        array_push($hidden, $attribute);

        $this->setHidden($hidden);

        // Make method chainable
        return $this;
    }
    /**
     * Convert appended collections into a list of attributes
     *
     * @param  object       $data       Model OR Collection
     * @param  string|array $levels     Levels to iterate over
     * @param  string       $attribute  The attribute we want to get listified
     * @param  boolean      $hideOrigin Hide the original relationship data from the result set
     * @return Model
     */
    public function listAttributes($data, $levels, $attribute = 'id', $hideOrigin = true) {

        // Set some defaults on first call of this function (because this function is recursive)
        if (!is_array($levels))
            $levels = explode('.', $levels);

        if ($data instanceof Illuminate\Database\Eloquent\Collection) { // Collection of Model objects
            // We are dealing with an array here, so iterate over its contents and use recursion to look deeper:
            foreach ($data as $row) {
                $this->listAttributes($row, $levels, $attribute, $hideOrigin);
            }
        } else {
            // Fetch the name of the current level we are looking at
            $curLevel = array_shift($levels);

            if (is_object($data->{$curLevel})) {
                if (!empty($levels)) {
                    // We are traversing the right section, but are not at the level of the list yet... Let's use recursion to look deeper:
                    $this->listAttributes($data->{$curLevel}, $levels, $attribute, $hideOrigin);
                } else {
                    // Hide the appended collection itself from the result set, if the user didn't request it
                    if ($hideOrigin)
                        $data->addHidden($curLevel);

                    // Convert Collection to Eloquent lists()
                    if (is_array($attribute)) // Use specific attributes as key and value
                        $data->{$curLevel . '_' . $attribute[0]} = $data->{$curLevel}->lists($attribute[0], $attribute[1]);
                    else // Use specific attribute as value (= numeric keys)
                        $data->{$curLevel . '_' . $attribute} = $data->{$curLevel}->lists($attribute);
                }
            }
        }

        return $data;
    }

}


<?php

class TrackerController extends BaseController {

    public function __construct() {
//$this->beforeFilter('auth',array('except'=>array('store','update')));
    }

    public function index() {
#$logs = Tracker::all();
        $logs = array();

//$browser = get_browser(null, true);
//if ($browser['browser']!='Default Browser') {
//	    return View::make('tracker.index',array('logs'=>$logs));
//} else {
        return Response::json(array(
                    'error' => false,
                    'logs' => $logs), 200
        );
//}
    }

    public function update() {
        $rules = array('data' => 'required');

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->messages();
            return Response::json(array('error' => true, 'messages' => $errors->toArray()), 200);
        } else {
            $input = Input::all();
            $data = json_decode($input['data']);
            if (sizeof($data->logs)) {
                foreach ($data->logs as $l) {
                    $log = new Tracker;
                    $log->username = $l->user_id;
                    $log->module = $l->module;
                    $log->data = $l->data;
                    $log->start_time = $l->start_time;
                    $log->end_time = $l->end_time;
                    $log->timetaken = (($l->end_time - $l->start_time) / 1000);
                    $log->created_at = date('Y-m-d h:m:s');
                    $log->modified_by = 1; // Tracker user id 
                    $log->save();
//Log::info("SavingLogUpdate: ".$log);
                }
            } else {
                return Response::json(array('error' => true, 'messages' => 'data missing'), 200);
            }
        }

        return Response::json(array('error' => false), 200);
    }

    public function store() {
        $rules = array('data' => 'required');

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->messages();
            return Response::json(array('error' => true, 'messages' => $errors->toArray()), 200);
        } else {
            $input = Input::all();
            $data = json_decode($input['data']);
            if (sizeof($data->logs)) {
                foreach ($data->logs as $l) {
                    $log = new Tracker;
                    $log->username = $l->user_id;
                    $log->module = $l->module;

                    $jsondata = json_decode($l->data);
                    if(strpos($l->data,'android.widget') !== false){
                        $processeddata = '{"target_category":"'.$jsondata->category.'","imei":"'.$jsondata->imei.'","battery":"'.$jsondata->battery.'","device":"'.$jsondata->device.'","due_date":"'.$jsondata->due_date.'","last_updated":"'.$jsondata->last_updated.'","ver":"'.$jsondata->ver.'","achieved_number":'.$jsondata->achieved_number.
                            ',"start_date":"'.$jsondata->start_date.'","target_type":"'.$jsondata->target_type.'","group_members":"'.$jsondata->group_members.'","target_id":'.$jsondata->target_id.',"id":'.$jsondata->target_id.'} ';
                        $log->data = $processeddata;
                    }else {
                        $log->data = $l->data;
                    }


                    $log->start_time = $l->start_time;
                    $log->end_time = $l->end_time;
                    $log->timetaken = (($l->end_time - $l->start_time) / 1000);
                    $log->created_at = date('Y-m-d h:m:s');
                    $log->modified_by = 1; // Tracker user id 
                    $log->save();

                    Log::info("Tracker saved .... ");

                    // check to see if group target setting
                    if(trim(preg_replace('/\s+/', ' ', $l->module)) == trim(preg_replace('/\s+/', ' ', 'Target Setting')) ) {
                        if ( strpos($l->data,'group_members') !== false){

                        Log::info("Target setting data coming through .....");
                        $justification = '';
                        $comment ='';
                        $target_detail='';
                        $target_id='';
                        $target_category='';
                        $target_group='';
                        $facility='';
                        $zone='';
                        $target_type='';
                        $target_month='';
                        $target_number='';
                        $achieved_number='';
                        $last_updated='';
                        $group_members='';


                        $data = json_decode($l->data);

                        if(strpos($l->data,'justification') !== false) $justification =  $data->justification; 
                        
                        if(strpos($l->data,'comments') !== false) $comment = $data->comments;

                        if(strpos($l->data,'target_detail') !== false) $target_detail = $data->target_detail;

                        if(strpos($l->data,'target_id') !== false) $target_id = $data->target_id;

                        if(strpos($l->data,'target_category') !== false) $target_category = $data->target_category;

                        if(strpos($l->data,'target_group') !== false) $target_group = $data->target_group;

                        if(strpos($l->data,'facility') !== false) $facility = $data->facility;

                        if(strpos($l->data,'zone') !== false) $zone = $data->zone;

                        if(strpos($l->data,'target_type') !== false) $target_type = $data->target_type;

                        if(strpos($l->data,'target_month') !== false) $target_month = $data->target_month;

                        if(strpos($l->data,'target_number') !== false) $target_number = $data->target_number;

                        if(strpos($l->data,'achieved_number') !== false) $achieved_number = $data->achieved_number;

                        if(strpos($l->data,'last_updated') !== false) $last_updated = $data->last_updated;

                        if(strpos($l->data,'group_members') !== false) $group_members = $data->group_members;

                        $target = new FacilityTarget;
                        $target->username = $l->user_id;
                        $target->facility =  $facility;
                        $target->zone = $data->zone;
                        $target->target_type = $target_type;
                        $target->target_category = $target_category;
                        $target->target_group = $target_group;
                        $target->target_id = $target_id;
                        $target->target_month = $target_month;
                        $target->target_number = $target_number;
                        $target->achieved_number = $achieved_number;
                        $target->target_detail = $target_detail;
                        $target->last_updated = $last_updated;
                        $target->comment = $comment;
                        $target->group_members =  $group_members;
                        $target->save();

                        Log::info("Facility target saved -> ". $target->target_id);

                        }else{
                        Log::info(" tracker data does not have group members ....");
                        }
                    }else{
                        Log::info(" tracker not of target setting ....");
                    }
                }
                } else {

//Log::info("SavingLogError: ".$log);

                    return Response::json(array('error' => true, 'messages' => 'data missing'), 200);
                }
            }

            return Response::json(array('error' => false), 200);
        
    }

    public function checkEventType($val) {
        $results = DB::select('select id from cch_event_type where name = ?', array($val));
        if (null == $results) {
            return false;
        } else if (count($results) > 0) {
            return true;
        }
        return false;
    }

}


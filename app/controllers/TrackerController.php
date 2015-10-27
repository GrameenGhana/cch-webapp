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
                    $log->data = $l->data;
                    $log->start_time = $l->start_time;
                    $log->end_time = $l->end_time;
                    $log->timetaken = (($l->end_time - $l->start_time) / 1000);
                    $log->created_at = date('Y-m-d h:m:s');
                    $log->modified_by = 1; // Tracker user id 
                    $log->save();

                    // check to see if group target setting
                    if($l->module == 'Target Setting' && strpos($l->data,'group_members') !== false){

                        $data = json_decode($l->data);

                        $target = new FacilityTarget;
                        $target->username = $l->user_id;
                        $target->facility =  $data->facility;
                        $target->zone = $data->zone;
                        $target->target_type = $data->target_type;
                        $target->target_category = $data->target_category;
                        $target->target_id = $data->target_id;
                        $target->target_month = $data->target_month;
                        $target->target_number = $data->target_number;
                        $target->achieved_number = $data->achieved_number;
                        $target->justification = $data->justification;
                        $target->comment = $data->comments;
                        $target->group_members =  $data->group_members;
                        $target->save();
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


<?php

class StoreLogs {

    public function fire($johb, $data)
    {
	    	$data = json_decode($input['data']);

	    	if (sizeof($data->logs)){
		        foreach($data->logs as $l)
	            {
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
		        }
		    }
    }
}

?>

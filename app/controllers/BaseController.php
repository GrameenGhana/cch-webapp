<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}


    public function createEventforJS($data, $start, $end, $bg='#0073b7', $border='#00a65a')
    {
        $data = preg_replace("/\n/","",$data);
        $events = json_decode($data);
       
        if ($events->deleted == 0)
        {
            $sd = $this->getDateFromMilli($start);
            $start = 'new Date('.$sd[0].', '.($sd[1]-1).', '.$sd[2].')';
        
            $ed = $this->getDateFromMilli($end);
            $end = 'new Date('.$ed[0].', '.($ed[1]-1).', '.$ed[2].')';

	    $location = (isset($events->location)) ? 'at '.$events->location : '';
            $event = array('title'=> addslashes($events->eventtype.' '.$location),
				   'eventtype'=>addslashes($events->eventtype),
                                   'start'=> $start,
                                   'end'  => $end,
                                   'backgroundColor' => $bg,
                                   'borderColor' => $border);
            return $event;
        }

        return null;
    }

    public function random_color_part() 
    {
        return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
    }

    public function random_color() 
    {
        return $this->random_color_part() . $this->random_color_part() . $this->random_color_part();
    }


    public function getDateFromMilli($milli)
    {
        $d = date('Y-m-d', ($milli/1000));
        return explode('-',$d);
    }

      /** 
     * Send a POST requst using cURL 
     * @param string $url to request 
     * @param array $post values to send 
     * @param array $options for cURL 
     * @return string 
     */ 

    public function curl_json_post($url, $post) 
    { 
	    $data = json_encode($post);

	    $ch = curl_init($url); 
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
		'Accept: application/json',
    		'Content-Type: application/json',      
    		'Content-Length: ' . strlen($data))
	    );                                                                                                                   
            if( ! $result = curl_exec($ch)) 
            { 
                return curl_error($ch); 
            } 
         
	   curl_close($ch); 
           return $result; 
    } 
}

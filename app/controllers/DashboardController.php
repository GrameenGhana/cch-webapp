<?php

class DashboardController extends BaseController {

    public function __construct()
    {
       $this->dashboard = new Dashboard;
    }

    public function moduleUsageByType()
    {
            $info = $this->dashboard->moduleUsage(Input::all());
            return Response::json(array('error' => false, 'info' => $info), 200 );
    }

	public function showStayingWell()
	{
	   	    return View::make('dashboards.stayingwell',array('dashboard'=>$this->dashboard));
	}
}

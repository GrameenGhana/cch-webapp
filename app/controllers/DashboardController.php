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

    public function indicatorsData()
    {
            $info = $this->dashboard->indicatorsData(Input::all());
            return Response::json(array('error' => false, 'info' => $info), 200);
    }

    public function indicatorsDataByCare()
    {
            $info = $this->dashboard->indicatorsDataByCare(Input::all());
            return Response::json(array('error' => false, 'info' => $info), 200);
    }

	public function showIndicators()
	{
            //return Response::json($this->dashboard->indicatorsData(array()));
	   	    return View::make('dashboards.indicators', array('dashboard'=>$this->dashboard));
	}

	public function showStayingWell()
	{
	   	    return View::make('dashboards.stayingwell', array('dashboard'=>$this->dashboard));
	}
}

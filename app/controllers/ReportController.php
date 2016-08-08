<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReportController
 *
 * @author skwakwa
 */
class ReportController  extends BaseController 
{
    public function index() 
    {
    if (Auth::check())
        {   
        $this->districtIds = User::getUserDistricts(Auth::user()->id);
        $this->isDistrictAdmin = false;

        if (strtolower(Auth::user()->role) == 'district admin' || strpos(strtolower(Auth::user()->role), "supervisor") != '') {
            $this->isDistrictAdmin = true;
        } else {
            $this->districtIds = "";
        }

        $years = array();
        for($i=date('Y'); $i >= 2012; $i--) { array_push($years, $i); }
        
        return View::make('reports.index',array('d'=> $this->districtIds, 'years'=>$years));
    }else{
             return View::make('login');
        }
    }
}


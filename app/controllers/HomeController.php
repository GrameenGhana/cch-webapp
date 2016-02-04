<?php

class HomeController extends BaseController {

    public function __construct()
    {
       $this->dashboard = new Dashboard;
    }

	public function showHome()
	{
	   if (in_array(strtolower(Auth::user()->role),array('supervisor','district supervisor'))) {
	        $nurses = array();
		    $seen = array();
	   	    foreach(Auth::user()->facilities as $k => $value) {
			    foreach($value->users as $k =>$v) {
				    if ((!in_array($v->id,$seen)) && $v->isNurse()) {
					    $v->myfac = $value->name;
					    array_push($nurses, $v);
					    array_push($seen, $v->id);
				    }
			    }
		    }
	   	    return View::make('index',array('nurses'=>$nurses,'dashboard'=>$this->dashboard));
	   } else {
	   	    return View::make('index',array('dashboard'=>$this->dashboard));
	   }
	}


	public function showLogin()
	{
	     return View::make('login');
	}

	public function doLogin()
	{
		// validate the info, create rules for the inputs
		$rules = array(
			'username' => 'required|alphaNum|min:3', 
			'password' => 'required|alphaNum|min:3' 
		);

		// run the validation rules on the inputs from the form
		$validator = Validator::make(Input::all(), $rules);
        
		if($validator->fails()) {
		   return Redirect::to('login')
				->with('flash_error','true')
				->withErrors($validator)
				->withInput(Input::except('password'));
		} else {
		   $userdata = array('username' => Input::get('username'),	'password' => Input::get('password'));		

		   if (Auth::attempt($userdata)) {
                $user = User::getByUsername($userdata['username']);
                $user->online = 1;
                $user->save();
			    return Redirect::to('/');	
		   } else {
			return Redirect::to('login')
				->with('flash_error','true')
				->withInput(Input::except('password'));
		   }
		}
	}

	public function doLogout()
	{
        $user = Auth::user();
        $user->online = 0;
        $user->save();
		Auth::logout(); 
		return Redirect::to('login'); 
	}
}

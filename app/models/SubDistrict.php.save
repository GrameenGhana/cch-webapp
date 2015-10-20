<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SubDistrict
 *
 * @author skwakwa
 */
class SubDistrict extends Eloquent { 

	protected $table = 'cch_sub_districts';

 public function district()
	{
	    return $this->hasOne('District','id','district_id');
	}
}


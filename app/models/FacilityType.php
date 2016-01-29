<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FacilityUser
 *
 * @author seth
 */
class FacilityType  extends Eloquent { 

	protected $table = 'cch_facility_type';
        
        
         public function facilities() {
        return $this->hasMany('Facility', 'facility_type','code');
    }

}


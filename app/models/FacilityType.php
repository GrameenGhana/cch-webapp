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

use Venturecraft\Revisionable\Revisionable;

class FacilityType  extends Revisionable { 

	protected $table = 'cch_facility_type';
    protected $revisionEnabled = true;
    protected $revisionCleanup = true; //Remove old revisions (works only when used with $historyLimit)
    protected $historyLimit = 500;
    protected $revisionCreationsEnabled = true;
    protected $revisionNullString = 'nothing';
    protected $revisionUnknownString = 'unknown';

    public function identifiableName()
    {
        return $this->name;
    }        
        
    public function facilities() {
        return $this->hasMany('Facility', 'facility_type','code');
    }
}


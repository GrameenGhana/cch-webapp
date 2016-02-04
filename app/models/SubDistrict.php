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

 use Venturecraft\Revisionable\Revisionable;

class SubDistrict extends Revisionable { 

	protected $table = 'cch_sub_districts';

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


    public function district()
	{
	    return $this->hasOne('District','id','district_id');
	}

    public function zones()
	{
	    return $this->hasMany('Zone','subdistrict_id');
	}
}


<?php

use Venturecraft\Revisionable\Revisionable;

class DistrictPopulation extends Revisionable { 
 
 protected $table = 'cch_district_population';

    protected $revisionEnabled = true;
    protected $revisionCleanup = true; //Remove old revisions (works only when used with $historyLimit)
    protected $historyLimit = 500;
    protected $revisionCreationsEnabled = true;
    protected $revisionNullString = 'nothing';
    protected $revisionUnknownString = 'unknown';

    public function identifiableName()
    {
        return $this->region.'-'.$this->district->name.'-'.$this->year;
    }
 
  public function district()
  {
      return $this->hasOne('District','id','district_id');
  }

 

}

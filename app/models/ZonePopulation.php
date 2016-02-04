<?php

class ZonePopulation extends Eloquent { 
 
    protected $table = 'cch_zone_population';

    protected $with = array('zone');

    protected $revisionEnabled = true;
    protected $revisionCleanup = true; //Remove old revisions (works only when used with $historyLimit)
    protected $historyLimit = 500;
    protected $revisionCreationsEnabled = true;
    protected $revisionNullString = 'nothing';
    protected $revisionUnknownString = 'unknown';

    public function identifiableName()
    {
        return $this->id;
    }


    public function subdistrict()
    {
      return $this->hasOne('SubDistrict','id','subdistrict_id');
    }
   
    public function zone()
    {
      return $this->hasOne('Zone','id','zone_id');
    }
}

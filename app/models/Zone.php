<?php

use Venturecraft\Revisionable\Revisionable;


class Zone extends Revisionable { 
 
 protected $table = 'cch_zones';

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
   
  public function subdistrict()
  {
      return $this->hasOne('SubDistrict','id','subdistrict_id');
  }

  public function facility()
  {
      return $this->hasOne('Facility','id','facility_id');
  }
}

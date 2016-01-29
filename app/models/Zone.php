<?php

class Zone extends Eloquent { 
 
 protected $table = 'cch_zones';

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

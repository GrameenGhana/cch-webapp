<?php

class ZonePopulation extends Eloquent { 
 
    protected $table = 'cch_zone_population';

    protected $with = array('zone');

    public function subdistrict()
    {
      return $this->hasOne('SubDistrict','id','subdistrict_id');
    }
   
    public function zone()
    {
      return $this->hasOne('Zone','id','zone_id');
    }
}

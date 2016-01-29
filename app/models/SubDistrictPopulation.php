<?php

class SubDistrictPopulation extends Eloquent { 
 
 protected $table = 'cch_sub_district_population';

public function district()
  {
      return $this->hasOne('District','id','district_id');
  }
   
    public function subdistrict()
  {
      return $this->hasOne('SubDistrict','id','subdistrict_id');
  }

 

}
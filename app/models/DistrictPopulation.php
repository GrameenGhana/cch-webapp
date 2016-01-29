<?php

class DistrictPopulation extends Eloquent { 
 
 protected $table = 'cch_district_population';

   
    public function district()
  {
      return $this->hasOne('District','id','district_id');
  }

 

}
<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ApiController
 *
 * @author liman
 */

class ApiController extends BaseController {

    public function getTargets(){

        $nurse_id = Input::get( 'nurse_id' );
         $year = date('Y');

       try {
        $user = User::whereRaw('username = ?',array($nurse_id) )->first();
        if(null != $user) {
            $zone = $user->zone_id;
            $zone_pop = ZonePopulation::whereRaw('zone_id = ? and year = ? ',array($zone,$year) )->first();

                
            $expected_pregnancies = DB::table('target_indicators')->where('type', '=', 'expected_pregnancies')->first();
            $expected_pregnancies_details = '[' . implode(',', array_map(array($this, 'detailsWrap'), explode(",", $expected_pregnancies->details))) . ']';
            $expected_pregnancies_total = $zone_pop->expected_pregnancies;

            $chn_6_to_11_mnths = DB::table('target_indicators')->where('type', '=', 'chn_6_to_11_mnths')->first();
            $chn_6_to_11_mnths_details = '[' . implode(',', array_map(array($this, 'detailsWrap'), explode(",", $chn_6_to_11_mnths->details))) . ']';
            $chn_6_to_11_mnths_total = $zone_pop->chn_6_11_mnths;

            $chn_0_to_11_mnths = DB::table('target_indicators')->where('type', '=', 'chn_0_to_11_mnths')->first();
            $chn_0_to_11_mnths_details = '[' . implode(',', array_map(array($this, 'detailsWrap'), explode(",", $chn_0_to_11_mnths->details))) . ']';
            $chn_0_11_mnths_total = $zone_pop->chn_0_to_11_mnths;

            $chn_12_to_23_mnths = DB::table('target_indicators')->where('type', '=', 'chn_12_to_23_mnths')->first();
            $chn_12_to_23_mnths_details = '[' . implode(',', array_map(array($this, 'detailsWrap'), explode(",", $chn_12_to_23_mnths->details))) . ']';
            $chn_12_to_23_mnths_total = $zone_pop->chn_12_23_mnths;

            $chn_0_to_23_mnths = DB::table('target_indicators')->where('type', '=', 'chn_0_to_23_mnths')->first();
            $chn_0_to_23_mnths_details = '[' . implode(',', array_map(array($this, 'detailsWrap'), explode(",", $chn_0_to_23_mnths->details))) . ']';
            $chn_0_to_23_mnths_total = $zone_pop->chn_0_to_23_mnths;

            $chn_24_to_59_mnths = DB::table('target_indicators')->where('type', '=', 'chn_24_to_59_mnths')->first();
            $chn_24_to_59_mnths_details = '[' . implode(',', array_map(array($this, 'detailsWrap'), explode(",", $chn_24_to_59_mnths->details))) . ']';
            $chn_24_to_59_mnths_total = $zone_pop->chn_24_to_59_mnths;

            $chn_less_than_5_yrs = DB::table('target_indicators')->where('type', '=', 'chn_less_than_5_yrs')->first();
            $chn_less_than_5_yrs_details = '[' . implode(',', array_map(array($this, 'detailsWrap'), explode(",", $chn_less_than_5_yrs->details))) . ']';
            $chn_less_than_5_yrs_total = $zone_pop->chn_less_than_5_yrs;

            $wifa_15_49_yrs = DB::table('target_indicators')->where('type', '=', 'wifa_15_49_yrs')->first();
            $wifa_15_49_yrs_details = '[' . implode(',', array_map(array($this, 'detailsWrap'), explode(",", $wifa_15_49_yrs->details))) . ']';
            $wifa_15_49_yrs_total = $zone_pop->wifa_15_49_yrs;

            $men_women_50_to_60_yrs = DB::table('target_indicators')->where('type', '=', 'men_women_50_to_60_yrs')->first();
            $men_women_50_to_60_yrs_details = '[' . implode(',', array_map(array($this, 'detailsWrap'), explode(",", $men_women_50_to_60_yrs->details))) . ']';
            $men_women_50_to_60_yrs_total = $zone_pop->men_women_50_to_60_yrs;

           return Response::json([ [ 'target_type'=> 'expected_pregnancies' , 'target_detail'=> $expected_pregnancies_details  , 
                                                 'target_category'=> $expected_pregnancies->category , 'target_group' => $expected_pregnancies->group ,
                                                 'target_overall' => $expected_pregnancies_total , 'target_id' => $expected_pregnancies->id ] ,

                                                [ 'target_type'=> 'chn_6_to_11_mnths' , 'target_detail'=> $chn_6_to_11_mnths_details  , 
                                                 'target_category'=> $chn_6_to_11_mnths->category , 'target_group' => $chn_6_to_11_mnths->group ,
                                                 'target_overall' => $chn_6_to_11_mnths_total , 'target_id' => $chn_6_to_11_mnths->id ],

                                                 [ 'target_type'=> 'chn_0_to_11_mnths' , 'target_detail'=> $chn_0_to_11_mnths_details  , 
                                                 'target_category'=> $chn_0_to_11_mnths->category , 'target_group' => $chn_0_to_11_mnths->group ,
                                                 'target_overall' => $chn_0_11_mnths_total , 'target_id' => $chn_0_to_11_mnths->id ] ,

                                                 [ 'target_type'=> 'chn_12_to_23_mnths' , 'target_detail'=> $chn_12_to_23_mnths_details  , 
                                                 'target_category'=> $chn_12_to_23_mnths->category , 'target_group' => $chn_12_to_23_mnths->group ,
                                                 'target_overall' => $chn_12_to_23_mnths_total , 'target_id' => $chn_12_to_23_mnths->id ] ,

                                                 [ 'target_type'=> 'chn_0_to_23_mnths' , 'target_detail'=> $chn_0_to_23_mnths_details  , 
                                                 'target_category'=> $chn_0_to_23_mnths->category , 'target_group' => $chn_0_to_23_mnths->group ,
                                                 'target_overall' => $chn_0_to_23_mnths_total , 'target_id' => $chn_0_to_23_mnths->id ] ,

                                                 [ 'target_type'=> 'chn_24_to_59_mnths' , 'target_detail'=> $chn_24_to_59_mnths_details  , 
                                                 'target_category'=> $chn_24_to_59_mnths->category , 'target_group' => $chn_24_to_59_mnths->group ,
                                                 'target_overall' => $chn_24_to_59_mnths_total , 'target_id' => $chn_24_to_59_mnths->id ] ,

                                                 [ 'target_type'=> 'chn_less_than_5_yrs' , 'target_detail'=> $chn_less_than_5_yrs_details  , 
                                                 'target_category'=> $chn_less_than_5_yrs->category , 'target_group' => $chn_less_than_5_yrs->group ,
                                                 'target_overall' => $chn_less_than_5_yrs_total , 'target_id' => $chn_less_than_5_yrs->id ] ,

                                                 [ 'target_type'=> 'chn_24_to_59_mnths' , 'target_detail'=> $chn_24_to_59_mnths_details  , 
                                                 'target_category'=> $chn_24_to_59_mnths->category , 'target_group' => $chn_24_to_59_mnths->group ,
                                                 'target_overall' => $chn_24_to_59_mnths_total , 'target_id' => $chn_24_to_59_mnths->id ] ,

          					[ 'target_type'=> 'wifa_15_49_yrs' , 'target_detail'=> $wifa_15_49_yrs_details  , 
                                                 'target_category'=> $wifa_15_49_yrs->category , 'target_group' => $wifa_15_49_yrs->group ,
                                                 'target_overall' => $wifa_15_49_yrs_total , 'target_id' => $wifa_15_49_yrs->id ] , 

                                                [ 'target_type'=> 'men_women_50_to_60_yrs' , 'target_detail'=> $men_women_50_to_60_yrs_details  , 
                                                 'target_category'=> $men_women_50_to_60_yrs->category , 'target_group' => $men_women_50_to_60_yrs->group ,
                                                 'target_overall' => $men_women_50_to_60_yrs_total , 'target_id' => $men_women_50_to_60_yrs->id ] ]);
            
        }else{
            $errors = array('User not found'); 
            return Response::json(array('error' => true, 'messages'=>$errors), 200);
        }
    }catch(Exception $ex){
        $errors = array('Server error'); 
            return Response::json(array('error' => true, 'messages'=>$errors), 200);

    }
        
            
    }

 // wrapping names with values 
   public  function detailsWrap($val) {
    return "{'name':'" . $val. "'}";
}


 public function getAllNurses(){
   
   $nurse_id = Input::get( 'nurse_id' );
   $user = User::whereRaw('username=?', array($nurse_id))->first();

   if (is_null($user)) {
    $errors = array('User Not Found');
    return Response::json(array('error' => true, 'messages' => $errors), 200);
} else {
    $s = $this->details($user);
    $facilities = array();
    foreach ($user->supervisedFacilities as $k => $v) {
        $facilities[] = array("id" => $v->id, "name" => $v->name);
    }


    $primary = array("id" =>@ $user->getPrimaryFacility()->facility_id, "name" => $user->getPrimaryFacilityDetails());

//            echo 'select * from cch.districts u where u.id =(select district from  cch.cch_facilities cf where cf.id='.$sup->getPrimaryFacility()->facility_id.' ) ';

    $district = DB::select('select * from cch.districts u where u.id =(select district from  cch.cch_facilities cf where cf.id=? ) ', array(@ $user->getPrimaryFacility()->facility_id));
    $dist = "No District";
    foreach($district as $d)
        {
            $dist = $d->name;

        }

    $subdistrict = DB::select('select * from cch.cch_sub_districts u where u.id =(select sub_district from  cch.cch_facilities cf where cf.id=? ) ', array(@ $user->getPrimaryFacility()->facility_id));
    $subdist = "No Sub District";
    foreach($subdistrict as $sd)
        {
            $subdist = $sd->name;

        }

    $zones = DB::select('select * from cch.cch_zones z where z.id =? ', array($user->zone_id));
    $zone = "No Zone";
    foreach($zones as $z)
        {
            $zone = $z->name;

        }

        $query = 'SELECT cu.username, cu.last_name,cu.first_name, IF(cu.ischn = 1, "Yes",IF(cu.ischn  = 0 ,"No","No")) as is_chn,d.name as district_name ,sd.name as subdistrict_name,cf.name as facility_name,cf.facility_type ,z.name as zone_name,cu.status  FROM cch.cch_users cu LEFT JOIN cch.cch_facility_user cfu  on   cu.id = cfu.user_id AND cfu.`primary` = 1   LEFT JOIN cch.cch_facilities cf on cf.id = cfu.facility_id LEFT JOIN cch.cch_sub_districts sd on sd.id=cf.sub_district  LEFT JOIN cch.districts d on d.id=cf.district LEFT JOIN cch.cch_zones z on z.id=cu.id  where cu.status="ACTIVE" group by cu.username';
        $groups=DB::select($query);
  
        return Response::json(array('error' => false,"user_id"=>$user->id, 'last_name' => $user->last_name, "first_name" => $user->first_name,
            "role" => $user->role, "ischn" => $user->ischn, "title" => $user->title,
            "primary_facility" => $primary,
            "district_name" => $dist,
            "subdistrict_name" => $subdist,
            "zone_name" => $zone,
            "groups"=>$groups,
            "userervised_facility" => $facilities));

    }
}

protected function details($user)
    {
            $facs = array();
            foreach($user->facilities as $k=>$v)
            {
                $nurses = array();
                foreach($v->nurses() as $n)
                      {
if($n->status=='ACTIVE' || $user->status=='TEST' && $n->status=='TEST') {     
         array_push($nurses, $n->toArray());
                }}
         array_push($facs, array('name'=>$v->name,
                                 'id'=>$v->id,
                                 'district'=>$v->facDistrict->name,
                                 'did'=>$v->facDistrict->id,
                                
 'region'=>$v->facDistrict->region,

                                'nurses'=>$nurses,
                                  ));
          }

            $s = array('name'=>$user->getName(),
                       'username'=>$user->username,
            'role'=>$user->role,
                       'facilities'=> $facs);

            return $s;
    }
    
}


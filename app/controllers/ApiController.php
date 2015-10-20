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
            return "{'status'=>'500','message'=>'User can not found.'}";
        }
        
        
    }

    function detailsWrap($val) {
    return "{'name':'" . $val. "'}";
}
    
}


<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IndicatorTracker
 *
 * @author dhutchful
 */
class IndicatorTracker  extends Eloquent { 

	protected $table = 'cch_indicators_tracker';
       
    protected $with = array('zone','indicator');

    public function zone()
    {
        return $this->belongsTo('Zone');
    }

    public function indicator()
    {
        return $this->belongsTo('Indicator');
    }

    public static function ByZoneYear($zone, $year)
    {
        return IndicatorTracker::whereRaw('zone_id=? and year=?', array($zone, $year))->orderBy('month')->orderBy('indicator_id')->get();
    }

    public static function ZoneActualsByNurse($nurseid, $year)
    {
        $info = array();

        $nurse = User::find($nurseid);

        if ($nurse != null)
        {
            $zd = IndicatorTracker::ByZoneYear($nurse->zone_id, $year);

            if (sizeof($zd)==0) { // Actuals don't exist- create records
                $pop = ZonePopulation::whereRaw('year=? and zone_id=?',array($year,$zone))->first();
                IndicatorTracker::updateTrackerTarget($pop);
                $zd = IndicatorTracker::ByZoneYear($zone, $year);
            }

            foreach($zd as $d)
            {
                $idc = ($d->indicator->type=='Maternal Health') ? $d->indicator->category : $d->indicator->agegroup;
                $c = $d->indicator->care;

                $monthNum  = $d->month;
                $dateObj   = DateTime::createFromFormat('!m', $monthNum);
                $month = $dateObj->format('F');    

                if (in_array($d->indicator->type, array_keys($info))) {
                    if (in_array($idc, array_keys($info[$d->indicator->type]))) {
                         if (in_array($c, array_keys($info[$d->indicator->type][$idc]))) {
                                array_push($info[$d->indicator->type][$idc][$c], array('month'=>$month, 'target'=>$d->target,
                                                                                       'actual'=>$d->actual));
                            } else {
                                $info[$d->indicator->type][$idc][$c] = array();
                                array_push($info[$d->indicator->type][$idc][$c], array('month'=>$month, 'target'=>$d->target,
                                                                                        'actual'=>$d->actual));
                            }
                    } else {
                        $info[$d->indicator->type][$idc] = array($c=>array());
                        array_push($info[$d->indicator->type][$idc][$c], array('month'=>$month, 'target'=>$d->target,'actual'=>$d->actual));
                    }
                } else {
                    $info[$d->indicator->type] = array($idc => array($c=> array( array('month'=>$month, 
                                                                                       'target'=>$d->target,'actual'=>$d->actual))));
                }
            }
        }
        return $info;
    }


    public static function ZoneActuals($zone, $year)
    {
        $info = array();
        $zd = IndicatorTracker::ByZoneYear($zone, $year);

        if (sizeof($zd)==0) { // Actuals don't exist- create records
            $pop = ZonePopulation::whereRaw('year=? and zone_id=?',array($year,$zone))->first();
            IndicatorTracker::updateTrackerTarget($pop);
            $zd = IndicatorTracker::ByZoneYear($zone, $year);
        }
        
        foreach($zd as $d)
        {
            $idc = ($d->indicator->type=='Maternal Health') ? $d->indicator->care : $d->indicator->care.' ('.$d->indicator->agegroup.')'; 

            $monthNum  = $d->month;
            $dateObj   = DateTime::createFromFormat('!m', $monthNum);
            $month = $dateObj->format('F'); 

            if (in_array($d->indicator->type, array_keys($info))) {
                if (in_array($idc, array_keys($info[$d->indicator->type]))) {
                    $info[$d->indicator->type][$idc][$d->month - 1] = array('id'=>$d->id, 'month'=>$month, 'target'=>$d->target,'actual'=>$d->actual);
                } else {
                    $info[$d->indicator->type][$idc] = array();
                    $info[$d->indicator->type][$idc][$d->month - 1] = array('id'=>$d->id, 'month'=>$month, 'target'=>$d->target,'actual'=>$d->actual);
                }
            } else {
               $info[$d->indicator->type] = array($idc => array());
               $info[$d->indicator->type][$idc][$d->month - 1] = array('id'=>$d->id, 'month'=>$month, 'target'=>$d->target,'actual'=>$d->actual);
            }
        }

        return $info;
    }

    public static function updateTrackerTarget($pop)
    {
        // check to see if info exits already
        $idcs = IndicatorTracker::ByZoneYear($pop->zone_id, $pop->year);

        if (sizeof($idcs) == 0) { // no records exist, create them
            $new = Indicator::all();
            foreach ($new as $n) {
                    for ($i=1; $i <= 12; $i++) { // months
                        $x = new IndicatorTracker;
                        $x->zone_id = $pop->zone_id;
                        $x->indicator_id = $n->id;
                        $x->year = $pop->year;
                        $x->month = $i;
                        $x->target = IndicatorTracker::getValueByIndicatorId($n->id, $pop); 
                        $x->modified_by=1;
                        $x->created_at = date('Y-m-d h:i:s');
                        $x->save();
                    }
            }
        }  else {
               foreach($idcs as $idc) {
                       $idc->target = IndicatorTracker::getValueByIndicatorId($idc->indicator_id, $pop); 
                       $idc->save();
               }
        }
     }

     public static function getValueByIndicatorId($iid, $pop)
     {
        $v = 0;

        if ($iid >= 1  and $iid <=14) { $v = $pop->chn_0_to_11_mnths; }
        if ($iid >= 15 and $iid <=16) { $v = $pop->chn_12_23_mnths; }
        if ($iid >= 16 and $iid <=17) { $v = $pop->chn_24_to_59_mnths; }
        if ($iid >= 18 and $iid <=27) { $v = $pop->expected_pregnancies; }
        if ($iid >= 28 and $iid <=29) { $v = $pop->wifa_15_49_yrs; }
        if ($iid >= 30) { $v = $pop->men_women_50_to_60_yrs; }

        return $v;
    }
}


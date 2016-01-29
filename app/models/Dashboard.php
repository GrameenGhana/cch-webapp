<?php

class Dashboard extends Eloquent {

	protected $connection = 'mysql2';
	protected $table = 'cch_info';

    private function getProperty($property)
    {
        $v = DB::connection('mysql2')->table('cch_info')
                    ->where('property', $property)
                    ->pluck('value');
        return $v;
    }

    public function lastRefreshDate()
    {
        return $this->getProperty('last_refresh_date');
    }

    public function projectLength($percentage=false)
    {
        $today = date('Y-m-d');
        $prsd = $this->getProperty('project_start_date');
        $prlm = $this->getProperty('project_length_in_months');

        return ($percentage) 
             ? round($this->DateDiff($prsd, $today, 'months') / $prlm,2) * 100 
             : $this->DateDiff($prsd, $today, 'months');
    }

    public function numDistricts($percentage=false)
    {
        $l = new Facility;
        $c = $l->districtCount();

        return ($percentage) 
            ? round($c / $this->getProperty('num_ghana_districts'),2) * 100  
            : $c;
    }

    public function numFacilities($percentage=false)
    {
        $l = new Facility;
        $c = $l->facilityCount();

        return ($percentage) 
            ? round($c / $this->getProperty('num_ghana_facilities'),2) * 100  
            : $c;
    }

    public function numNurses($percentage=false)
    {
        $s = new User;
        $c = $s->nurseCount();

        return ($percentage) 
            ? round($c / $this->getProperty('num_ghana_nurses'),2) * 100  
            : $c;
    }

    public function moduleUsage($params=null)
    {
        $data = array();

        $where = Dashboard::buildWhere($params);

        $d =  DB::connection('mysql2')->table('moduleusage')
                        ->select(DB::raw('module, CEILING(sum(mins_spent)) as timespent'))
                        ->whereRaw($where)
                        ->whereRaw('module in ("Achievement Center","Event Planner","Learning Center","Point of Care","Staying Well")')
                        ->groupBy('module')
                        ->orderBy('module')
                        ->get();

        foreach($d as $p) { $data[$p->module] = round($p->timespent / 3600,2); }

        return $data;
    }

    public function moduleUsageBySection($params=null)
    {
        $data = array('Achievement Center'=>array(),
                      'Event Planner'=>array(),
                      'Learning Center'=>array(),
                      'Point of Care'=>array(),
                      'Staying Well'=>array());

        $where = Dashboard::buildWhere($params);

        $d =  DB::connection('mysql2')->table('moduleusage')
                        ->select(DB::raw('module, "Main Page" as section, CEILING(sum(mins_spent)) as timespent'))
                        ->whereRaw($where)
                        ->whereRaw('module in ("Achievement Center","Event Planner")')
                        ->groupBy('module')
                        ->groupBy('section')
                        ->orderBy('section')
                        ->get();
        foreach($d as $p) { array_push($data[$p->module], array($p->section, round($p->timespent/3600,2))); }

        $d =  DB::connection('mysql2')->table('pointofcare')
                        ->select(DB::raw('"Point of Care" as module, section, CEILING(sum(mins_spent)) as timespent'))
                        ->whereRaw($where)
                        ->groupBy('module')
                        ->groupBy('section')
                        ->orderBy('section')
                        ->get();
        foreach($d as $p) { array_push($data[$p->module], array($p->section, round($p->timespent/3600,2))); }

        $d =  DB::connection('mysql2')->table('learningcenter_access')
                        ->select(DB::raw('"Learning Center" as module, section, CEILING(sum(mins_spent)) as timespent'))
                        ->whereRaw($where)
                        ->groupBy('module')
                        ->groupBy('section')
                        ->orderBy('section')
                        ->get();
        foreach($d as $p) { array_push($data[$p->module], array($p->section, round($p->timespent/3600,2))); }

        $d =  DB::connection('mysql2')->table('stayingwell')
                        ->select(DB::raw('"Staying Well" as module, section, CEILING(sum(mins_spent)) as timespent'))
                        ->whereRaw($where)
                        ->groupBy('module')
                        ->groupBy('section')
                        ->orderBy('section')
                        ->get();
        foreach($d as $p) { array_push($data[$p->module], array($p->section, round($p->timespent/3600,2))); }

        return $data;
    }

    /*** Indicators **/
    private function getitype($params)
    {
        if ($params==null) { return 'Child Health'; }
        
        if (isset($params['type'])) {
            return (in_array($params['type'], array('Child Health','Maternal Health','Others'))) ? $params['type'] : 'Child Health';
        } else {
            return 'Child Health'; 
        }
    }

    public function indicatorsData($params=null)
    {
        $data = array();

        $type = $this->getitype($params); 
        $where = Dashboard::buildWhere($params, false, true);
        $groupby = ($type=='Maternal Health') ? 'category' : 'agegroup';

        $d =  DB::table('cch_indicators_tracker')
                ->join('cch_indicators', 'cch_indicators_tracker.indicator_id','=','cch_indicators.id')
                ->select(DB::raw("$groupby as g, (SUM(actual) / SUM(target)) * 100 as c"))
                        ->whereRaw($where)
                        ->whereRaw("cch_indicators.type='$type'")
                        ->groupBy($groupby)
                        ->get();

        foreach($d as $p) { $data[$p->g] = $p->c; }

        return $data;
    }

    public function indicatorsDataByCare($params=null)
    {
        $data = array();

        $type = $this->getitype($params); 
        $where = Dashboard::buildWhere($params, false, true);
        $groupby = ($type=='Maternal Health') ? 'category' : 'agegroup';

        $d =  DB::table('cch_indicators_tracker')
                ->join('cch_indicators', 'cch_indicators_tracker.indicator_id','=','cch_indicators.id')
                ->select(DB::raw("$groupby as g, care, (SUM(actual) / SUM(target)) * 100 as c"))
                        ->whereRaw($where)
                        ->whereRaw("cch_indicators.type='$type'")
                        ->groupBy($groupby)
                        ->groupBy('care')
                        ->get();

        foreach($d as $p) {
            if (in_array($p->g, array_keys($data))) {
                array_push($data[$p->g], array($p->care, $p->c));
            } else {
                $data[$p->g] = array();
                array_push($data[$p->g], array($p->care, $p->c));
            }
        }

        return $data;
    }

    public function indicatorsDataByNurse($nurse)
    {
        $data = array();
        $year = date('Y');
        $zonedata = IndicatorTracker::ZoneActualsByNurse($nurse, $year);
        return $zonedata;
    }

    public function indicatorsDataByZone($zone)
    {
        $data = array();
        $year = date('Y');
        $zonedata = IndicatorTracker::ZoneActuals($zone, $year);
        return $zonedata;
    }


    /*** End Indicators  **/


    /*** Staying Well **/
    public function swPlans($params=null)
    {
        $data = array();

        $where = Dashboard::buildWhere($params);

        $d =  DB::connection('mysql2')->table('stayingwell_plans')
                        ->select(DB::raw('plan, COUNT(nurseid) as c'))
                        ->whereRaw($where)
                        ->groupBy('plan')
                        ->orderBy('plan')
                        ->get();

        foreach($d as $p) { $data[ucfirst($p->plan)] = $p->c; } 

        return $data;
    }

    public function swPlansByProfile($params=null)
    {
        $data = array('Appearance'=>array(),
                      'Strength'=>array(),
                      'Michael'=>array());

        $where = Dashboard::buildWhere($params);

        $d =  DB::connection('mysql2')->table('stayingwell_plans')
                        ->select(DB::raw('plan, profile, COUNT(nurseid) as c'))
                        ->whereRaw($where)
                        ->groupBy('plan')
                        ->groupBy('profile')
                        ->orderBy('profile')
                        ->get();

        foreach($d as $p) { 
            if (in_array(ucfirst($p->plan), array_keys($data))) {
                array_push($data[ucfirst($p->plan)], array(ucfirst($p->profile), $p->c)); 
            }

        }

        return $data;
    }

    /*** End Staying well **/

    protected function makePercentage($data)
    {
        $total = 0;
        foreach($data as $c) { $total += $c; }
        foreach($data as $k => $c) { $data[$k] = round($c/$total,2) * 100; }
        return $data;
    }


    /** HTML utility methods **/
    public function locationsForSelect()
    {
        $l = new Facility;
        return array('all'=>'All','Regions'=>$l->regions(),
                     'Districts'=>$l->districts(),
                     'Facilities'=>$l->facilities());
    }

    public function locationZonesForSelect()
    {
        $locs = array('all'=>'All');
        $districtIds = User::getUserDistricts(Auth::user()->id);
        $locations = District::with('subdistricts.zones')->whereIn('id', explode(',',$districtIds))->get();

        foreach($locations as $district)
        {
                foreach($district->subdistricts as $sd) {
                        $x = array();
                        foreach($sd->zones as $z) { $x[$z->id]=$z->name;  }
                        $locs[$sd->name] =  $x;  
                }
        }
        return $locs; 
    }

    /** Utility functions **/

    private function DateDiff($date1, $date2, $format='long')
    {
        $info = $this->dateDifference($date1, $date2);

        switch($format)
        {
          case 'years': $diff = $info[0]; break;
          case 'months': $diff = ($info[0]*12)+($info[1] + round($info[2] / 31,0)); break;
          case 'days': $diff = ($info[0]*365) + ($info[1]*30) + $info[2]; break;

          default:
            $diff = $info[0].' years, '.$info[1].' months, '.$info[2].' days';
        }
        return $diff;
    }

    private function dateDifference($startDate, $endDate)
    {
            $startDate = strtotime($startDate);
            $endDate = strtotime($endDate);

            if ($startDate === false || $startDate < 0 ||
                $endDate === false || $endDate < 0 || $startDate > $endDate)
                return false;

            $years = date('Y', $endDate) - date('Y', $startDate);

            $endMonth = date('m', $endDate);
            $startMonth = date('m', $startDate);

            // Calculate months 
            $months = $endMonth - $startMonth;
            if ($months <= 0)  {
                $months += 12;
                $years--;
            }
            if ($years < 0)
                return false;

            // Calculate the days 
            $offsets = array();
            if ($years > 0)
                $offsets[] = $years . (($years == 1) ? ' year' : ' years');
            if ($months > 0)
                $offsets[] = $months . (($months==1) ? ' month' : ' months');

            $offsets = count($offsets) > 0 ? '+'.implode(' ',$offsets):'now';

            $days = $endDate - strtotime($offsets, $startDate);
            $days = date('z', $days);

            return array($years, $months, $days);
   }

    protected static function buildWhere($params, $strmonth=true, $zones=false)
    {
        if (is_null($params)) { return "1=1"; }

        $params = Dashboard::processInput($params);

        // process dates
        $where = "`year` BETWEEN  YEAR('".$params['startdate']."') AND YEAR('".$params['enddate']."')";
        $where .= " AND MONTH(str_to_date(`month`,'%M')) BETWEEN  MONTH('".$params['startdate']."') AND MONTH('".$params['enddate']."')";
        if (!$strmonth) { $where = preg_replace("/MONTH\(str_to_date\(`month`,'%M'\)\)/",'`month`',$where); }

        // process location option
        if (! is_null($params['location']))
        {
            if (!in_array('all',$params['location'])) 
            {
                $facs = array();
                $l = new Facility;

                foreach($params['location'] as $f)
                {
                    if (preg_match("/Region/",$f)) {
                        $ids = $l->facilities($f,null);
                        foreach($ids as $id =>$v) { array_push($facs, $id); }

                    } else if (preg_match("/District/",$f)) {
                        $ids = $l->facilities(null,$f);
                        foreach($ids as $id =>$v) { array_push($facs, $id); }

                    } else {
                        array_push($facs, $f);
                    }
                } 

                if (! empty($facs)) {
                    $col = ($zones) ? 'zone_id' : 'motech_facility_id in';
                    $where .= " AND $col in (".implode(',',$facs).")";
                }
            }
        }

        return $where;
    }

    public static function processInput($data)
    {
        $params = array('enddate','location','percentage', 'startdate');

        // set unlisted or empty param values to null
        foreach($params as $param) {
            if (!in_array($param, array_keys($data)) || $data[$param]=='') { $data[$param] = null; }
        }

        // fix or set defaults for params
        foreach($data as $key => $val) {
            if (in_array($key,array('startdate','enddate'))) {
                if (preg_match('/\d{2}\/\d{2}\/\d{4}/',$val)) {
                    list($m,$d,$y) = preg_split('/\//',$val);
                    $data[$key] =  "$y-$m-$d";
                } else {
                    $data[$key] = ($key=='startdate') ? '2014-06-01' : date('Y-m-d');
                }
            }

            if ($key=='percentage') { $data[$key] = ($val==1) ? true : false; }
        }

        return $data;
    }
}

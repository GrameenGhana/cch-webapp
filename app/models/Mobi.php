<?php

class Mobi extends Eloquent {

	protected $table = 'ma_mobi_matrix';
    protected $primaryKey = 'motech_id';

    public function scopeMM($query)
    {
        return $query->whereRaw('mm_start_date is not null');
    }

    public function scopePregnant($query)
    {
        return $query->where('client_type','=','Pregnant Woman');
    }

    public function scopeInfant($query)
    {
        return $query->where('client_type','=','Infant');
    }

    public function scopeCurrent($query)
    {
        return $query->whereRaw('DATE(mm_end_date) < NOW()');
    }

    public function clientCount()
    {
       $count =  DB::table($this->table)
                        ->select(DB::raw('count(distinct(motech_id)) as num'))
                        ->get();

        return $count[0]->num;
    }

    public static function mmInfantCount()
    {
         $count =  DB::table('ma_mobi_matrix')
                        ->select(DB::raw('count(distinct(motech_id)) as num'))
                        ->where('client_type','=','Infant')
                        ->whereRaw('mm_start_date is not null')
                        ->get();

        return $count[0]->num;
    }

    public static function clientTypeCount($params=null)
    {
        $data = array();

        $where = Mobi::buildWhere($params);

        $d =  DB::table('ma_mobi_matrix')
                        ->select(DB::raw('client_type, count(distinct(motech_id)) as num'))
                        ->whereRaw($where)
                        ->groupBy('client_type')
                        ->orderBy('client_type')
                        ->get();

        foreach($d as $p) { $data[$p->client_type] = $p->num; }

        return $data;
    }

    public static function mmClientTypeCount($params=null)
    {
        $data = array();

        $where = Mobi::buildWhere($params);

        $d =  DB::table('ma_mobi_matrix')
                        ->select(DB::raw('IF(gender="M" and client_type!="Infant","Men",client_type) as client_type, count(distinct(motech_id)) as num'))
                        ->whereRaw($where)
                        ->whereRaw('mm_start_date is not null')
                        ->groupBy('client_type')
                        ->orderBy('client_type')
                        ->get();

        foreach($d as $p) { $data[$p->client_type] = $p->num; }

        return $data;
    }

    public static function mmDropoutTypeCount($params=null)
    {
        $data = array();

        $where = Mobi::buildWhere($params);

        $d =  DB::table('ma_mobi_matrix')
                        ->select(DB::raw('IF(dropout=1,"Dropped Out","Enrolled") as status, count(distinct(motech_id)) as num'))
                        ->whereRaw($where)
                        ->whereRaw('mm_start_date IS NOT null')
                        ->groupBy('dropout')
                        ->get();

        foreach($d as $p) { $data[$p->status] = $p->num; }

        return $data;
    }

    public static function mmActiveListenerCountByPhoneownership($params=null)
    {
        $data = array();

        $where = Mobi::buildWhere($params);

        $sel = 'phone_ownership as p, listener_status as ls, count(distinct(motech_id)) as num , (select count(distinct(motech_id)) from ma_mobi_matrix where mm_start_date is not null and phone_ownership=p) as total ';
        $d =  DB::table('ma_mobi_matrix')
                        ->select(DB::raw($sel))
                        ->whereRaw($where)
                        ->whereRaw('mm_start_date IS NOT null')
                        ->groupBy('phone_ownership')
                        ->groupBy('listener_status')
                        ->get();

        foreach($d as $p) { 
                if (in_array($p->ls, array_keys($data))) {
                    if($p->p=='PERSONAL') { $data[$p->ls][0] = round($p->num/$p->total,2) * 100; }
                    if($p->p=='HOUSEHOLD') { $data[$p->ls][1] = round($p->num/$p->total,2) * 100; }
                    if($p->p=='PUBLIC') { $data[$p->ls][2] = round($p->num/$p->total,2) * 100; }
                } else {
                    $data[$p->ls] = array(0,0,0);
                    if($p->p=='PERSONAL') { $data[$p->ls][0] = round($p->num/$p->total,2) * 100; }
                    if($p->p=='HOUSEHOLD') { $data[$p->ls][1] = round($p->num/$p->total,2) * 100; }
                    if($p->p=='PUBLIC') { $data[$p->ls][2] = round($p->num/$p->total,2) * 100; }
                } 
        }

        return $data;
    }

    public static function mmPhoneownershipCount($params=null)
    {
        $data = array();

        $where = Mobi::buildWhere($params);

        $d =  DB::table('ma_mobi_matrix')
                        ->select(DB::raw('phone_ownership as status, count(distinct(motech_id)) as num'))
                        ->whereRaw($where)
                        ->whereRaw('mm_start_date IS NOT null')
                        ->groupBy('phone_ownership')
                        ->get();

        foreach($d as $p) { $data[$p->status] = $p->num; }

        return $data;
    }

    public static function mmAgeByClientType($params=null)
    {
        $data = array();

        $where = Mobi::buildWhere($params);

        $d =  DB::table('ma_mobi_matrix')
                        ->select(DB::raw('client_type, IF(client_type="Infant",reg_age,CEILING(reg_age/52)) as age, count(motech_id) as num'))
                        ->whereRaw($where)
                        ->whereRaw('mm_start_date is not null')
                        ->groupBy('client_type')
                        ->groupBy('age')
                        ->orderBy('client_type')
                        ->get();

        foreach($d as $p) {
                if (in_array($p->client_type,array_keys($data))) {
                    array_push($data[$p->client_type], array($p->age,$p->num));
                } else {
                    $data[$p->client_type] = array(array($p->age,$p->num));
                }
        }

        return $data;
    }

    public static function mmPregnantWomenCountByTrimester($params=null)
    {
        $data = array();

        $where = Mobi::buildWhere($params);

        $d =  DB::table('ma_mobi_matrix')
                        ->select(DB::raw('trimester, count(motech_id) as num'))
                        ->whereRaw($where)
                        ->whereRaw('trimester is not NULL AND client_type="Pregnant Woman" AND mm_start_date is not null')
                        ->groupBy('trimester')
                        ->orderBy('trimester')
                        ->get();

        foreach($d as $p) {
                $data[$p->trimester] = $p->num;
        }

        return $data;
    }


    protected static function buildWhere($params)
    {
        if (is_null($params)) { return "1=1"; }

        $params = Mobi::processInput($params);

        // process dates
        $where = "DATE(start_date) BETWEEN '".$params['startdate']."' AND '".$params['enddate']."'";

        // process enrollment age option
        $enrollmentage = $params['enrollmentage'];
        if (!is_null($enrollmentage) && !in_array('all',$enrollmentage))
        {
            $t = '';
            if (sizeof($enrollmentage)==3) { // 15-24,25-50,> 50
                $t = " AND reg_age BETWEEN (15*52) AND (200*52)";
            } elseif (in_array(2,$enrollmentage) && sizeof($enrollmentage)==1) { // only > 50
                $t = " AND reg_age > (50*52)";
            } elseif (in_array(2,$enrollmentage) && in_array(1,$enrollmentage) && sizeof($enrollmentage) ==2) { // > 50 and 25-50 
                $t = " AND ((reg_age BETWEEN (25*52) AND (50*52)) OR reg_age > (50*52))";
            } elseif (in_array(2,$enrollmentage) && in_array(0,$enrollmentage) && sizeof($enrollmentage) == 2) { // > 50 and 15-24 
                $t = " AND ((reg_age BETWEEN (15*52) AND (24*52)) OR reg_age > (50*52))";
            } elseif (in_array(0,$enrollmentage) && sizeof($enrollmentage)==1) { // only 15-24 
                $t = " AND reg_age BETWEEN (15*52) AND (24*52)";
            } elseif (in_array(0,$enrollmentage) && in_array(1,$enrollmentage) && sizeof($enrollmentage) == 2) { // 15-24 and 25-50 
                $t = " AND reg_age BETWEEN (15*52) AND (50*52)";
            } else {
                $t = " AND reg_age BETWEEN (25*52) AND (50*52)";
            }

            $where .= $t; 
        }

        // process client type
        $clienttype = $params['clienttype'];
        if (!is_null($clienttype) && !in_array('all',$clienttype))
        {
            $t = ' AND ';

            foreach($clienttype as $ct)
            {
                $t .= ($t==' AND ') ? '' : ' OR ';

                if ($ct =='Reproductive Age') { 
                    $t .= " (client_type = 'Reproductive Age' AND gender='F')"; 
                } elseif ($ct == 'Men') {
                    $t .= "  (gender='M' AND reg_age > (15*52)) ";
                } else {
                    $t .= " (client_type = '$ct') ";
                }
            } 
            $where .= $t;     
        }

        // process location option
        if (! is_null($params['location']))
        {
            if (!in_array('all',$params['location'])) 
            {
                $facs = array();
                $l = new Location;

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
                    $where .= " AND facility_id in (".implode(',',$facs).")";
                }
            }
        }

        // process parity option
        if (! is_null($params['parity']) && $params['parity'] != 'all') {
            $where .= ($params['parity']=='2') ? ' AND parity > 1' : " AND parity = ".$params['parity'];
        }

        // process phone ownership option
        if (! is_null($params['phoneownership'])) {
            if(!in_array('all',$params['phoneownership'])) {
                $o = array();
                foreach($params['phoneownership'] as $f) { array_push($o, $f); }
                if (! empty($o)) {
                    $where .= " AND phone_ownership in ('".implode("','",$o)."')";
                }
            }
        }
        // process trimester option
        if (!is_null($params['trimester']) && !in_array('all',$params['trimester']))
        {
            $where .= " AND trimester in ('".implode("','",$params['trimester'])."')";
        }

        return $where;
    }

    public static function processInput($data)
    {
        $params = array('enddate','enrollmentage','location','percentage','phoneownership','parity',
                        'trimester','startdate','clienttype');

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
                    $data[$key] = ($key=='startdate') ? '2010-07-05' : date('Y-m-d');
                }
            }

            if ($key=='percentage') { $data[$key] = ($val==1) ? true : false; }
        }

        return $data;
    }
}

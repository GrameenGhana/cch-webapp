<?php

use Venturecraft\Revisionable\Revisionable;

class Facility extends Revisionable {

    protected $table = 'cch_facilities';
    protected $dates = ['deleted_at'];

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


    public function modifier() {
        return $this->hasOne('User', 'id', 'modified_by');
    }

    public function facDistrict() {
        return $this->hasOne('District', 'id', 'district');
    }

    public function facSubDistrict() {
        return $this->hasOne('SubDistrict', 'id', 'sub_district');
    }

    public function users() {
        return $this->belongsToMany('User', 'cch_facility_user')->where("primary",1);
    }

    public function nurses($complex=true) {  
        $nurses = array();
        $seen = array();

        foreach ($this->users()->withTrashed()->get() as $l => $u) {
	
           if ((!in_array($u->id, $seen)) && $u->isNurse()) {
            if ((!in_array($u->id, $seen))) {

                //print $u->id.'-'.$u->username."\n";
                $u->myfac = $this->name;

                if ($complex)
                {
                   $u->calendar = $u->events(true);
                   $u->courses = $u->courses();
                   $u->targets = $u->targets();
                }
                array_push($nurses, $u);
                array_push($seen, $u->id);
            }
          }
        }

        return $nurses;
    }

    public function events($limit=false) {
        $events = array();
        foreach ($this->nurses() as $u) {
            $evts = $u->events($limit);
            foreach ($evts as $k => $e) {
                $events[$k] = $e;
            }
        }
        return $events;
    }

    public function regions() 
    {
        $regions = array();

        $list = DB::table($this->table)
                ->select(DB::raw('distinct(region) as region'))
                ->whereRaw('district not in ("Ghana","Kassena-Nankana West","Other")')
                ->orderBy('region')
                ->get();

        foreach ($list as $r) {
            if ($r->region != 'unknown') 
            { 
                if (!preg_match('/Region/', $r->region)) { $r->region .= ' Region'; }
                $regions[$r->region] = $r->region; 
            }
        }

        return $regions;
    }

    public function districts() 
    {
        $districts = array();

        $list = DB::table('cch_district')
                ->select(DB::raw('name'))
                ->whereRaw('name not in ("Ghana","Kassena-Nankana West","Other")')
                ->orderBy('name')
                ->get();

        foreach ($list as $d) 
        {
            if (!preg_match('/District/', $d->name)) {
                $d->name .= ' District';
            }
            $districts[$d->name] = $d->name;
        }

        return $districts;
    }

    public function facilities($region = null, $district = null) 
    {
        $facilities = array();

        $region = ($region == null) ? $region : preg_replace('/\s*Region/', '', $region);
        $district = ($district == null) ? $district : preg_replace('/\s*District/', '', $district);

        $where = 'district not in ("Ghana","Kassena-Nankana West","Other")';
        $where .= ($region == null) ? '' : " AND fs.region LIKE '$region%'";
        $where .= ($district == null) ? '' : " AND d.name LIKE '$district%'";

        $sql = "SELECT distinct(fs.name) as f, fs.id
                  FROM $this->table fs
                  JOIN districts d ON d.id = fs.district
                 WHERE $where
                 ORDER BY f"; 

        $list = DB::select(DB::raw($sql));

        foreach ($list as $f) {
            $facilities[$f->id] = $f->f;
        }

        return $facilities;
    }

    public function districtCount() {
        $count = DB::table($this->table)
                ->select(DB::raw('count(distinct(district)) as num'))
                ->whereRaw('district not in ("Ghana","Kassena-Nankana West","Other")')
                ->get();
        return $count[0]->num;
    }

    public function facilityCount() {
        $count = DB::table($this->table)
                ->select(DB::raw('count(distinct(name)) as num'))
                ->whereRaw('district not in ("Ghana","Kassena-Nankana West","Other")')
                ->get();

        return $count[0]->num;
    }
}

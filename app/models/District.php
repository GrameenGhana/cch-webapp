<?php

use Venturecraft\Revisionable\Revisionable;

class District extends Revisionable { 

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

    public function users()
    {
      	return $this->belongsToMany('Facilities','cch_facilties');
    } 

    public function subdistricts()
    {
        return $this->hasMany('SubDistrict','district_id');
    }

    public function nurses()
    {
           $nurses = array();
           $seen = array();

           foreach($this->users as $l =>$u) {
               if ((!in_array($u->id,$seen)) && $u->isNurse()) {
            
                     $u->myfac = $this->name;
                     $u->calendar = $u->events(true);
                     $u->courses = $u->courses();
                     $u->targets = $u->targets();
                     array_push($nurses, $u);
                     array_push($seen, $u->id);
               }
           }

           return $nurses;
    }

    public function events()
    {
        $events = array();
        foreach($this->nurses() as $u) {
                $evts = $u->events();
                foreach($evts as $k => $e) {
                        $events[$k] = $e;
                }
        }
        return $events;
    }

    public function regions()
    {
       $regions = array();

       $list = DB::table($this->table)
                        ->select(DB::raw('distinct(region)'))
                        ->whereRaw('district not in ("Ghana","Kassena-Nankana West","Other")')
                        ->get();
        foreach($list as $r) {
                if (!preg_match('/Region/',$r->region)) {$r->region .= ' Region'; }
                $regions[$r->region] = $r->region;
        }

        return $regions;
    }

    public function districts()
    {
        $districts = array();

        $list = DB::table($this->table)
                        ->select(DB::raw('distinct(district)'))
                        ->whereRaw('district not in ("Ghana","Kassena-Nankana West","Other")')
                        ->orderBy('district')
                        ->get();

        foreach($list as $d) {
                if (!preg_match('/District/',$d->district)) {$d->district .= ' District'; }
                $districts[$d->district] = $d->district;
        }

        return $districts;
    }

    public function facilities($region=null, $district=null)
    {
        $facilities = array();

        $region = ($region == null) ? $region : preg_replace('/Region/','',$region);
        $district = ($district == null) ? $district : preg_replace('/District/','',$district);

        $where = 'district not in ("Ghana","Kassena-Nankana West","Other")';
        $where .= ($region == null) ? '' : " AND region LIKE '$region%'";
        $where .= ($district == null) ? '' : " AND district LIKE '$district%'";

        $list = DB::table($this->table)
                        ->select(DB::raw('distinct(name) as f, id'))
                        ->orderBy('f')
                        ->whereRaw($where)
                        ->get();

        foreach($list as $f) {
                $facilities[$f->id] = $f->f;
        }

        return $facilities;
    }

    public function districtCount()
    {
        $count = DB::table($this->table)
                        ->select(DB::raw('count(distinct(district)) as num'))
                        ->whereRaw('district not in ("Ghana","Kassena-Nankana West","Other")')
                        ->get();
        return $count[0]->num;
    }

    public function facilityCount()
    {
        $count =  DB::table($this->table)
                        ->select(DB::raw('count(distinct(name)) as num'))
                        ->whereRaw('district not in ("Ghana","Kassena-Nankana West","Other")')
                        ->get();

        return $count[0]->num;
    }
}

<?php

class KSAStatus extends Eloquent {

    protected $table = 'cch_ksa_status';

    public function modifier() {
        return $this->hasOne('User', 'id', 'modified_by');
    }

    public function supervisor() {
        return $this->hasOne('User', 'username', 'created_by');
    }

    public function nurse() {
        return $this->hasOne('User', 'id', 'userid');
    }

    public function course() {
        return $this->hasOne('Course', 'id', 'courseid');
    }

    public static function findRec($userid, $courseid)
    {
        return  KSAStatus::whereRaw('userid='.$userid.' and courseid='.$courseid)->get();
    }
}

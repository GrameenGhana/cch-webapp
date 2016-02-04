<?php

use Venturecraft\Revisionable\Revisionable;

class Device extends Revisionable { 

	protected $table = 'cch_devices';

    protected $revisionEnabled = true;
    protected $revisionCleanup = true; //Remove old revisions (works only when used with $historyLimit)
    protected $historyLimit = 500; 
    protected $revisionCreationsEnabled = true;
    protected $revisionNullString = 'nothing';
    protected $revisionUnknownString = 'unknown';

    public function identifiableName()
    {
        return $this->type.' - '.$this->imei;
    }

	public function modifier()
	{
	    return $this->hasOne('User','id','modified_by');
	}

    public function user()
    {
	    return $this->hasOne('User','id','user_id');
    } 

    public function getUserName()
    {
        return ($this->user == null) ? 'Unassigned' : $this->user->getName();
    } 
}

<?php

class Device extends Eloquent { 

	protected $table = 'cch_devices';

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

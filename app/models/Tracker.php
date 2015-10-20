<?php

class Tracker extends Eloquent { 

	protected $table = 'cch_tracker';

	public function modifier()
	{
	       return $this->hasOne('User','id','modified_by');
	}

	public function user()
	{
	       return $this->hasOne('User','username','username');
	}
}

<?php

use Venturecraft\Revisionable\Revisionable;

class Region extends Revisionable { 

    protected $table = 'cch_region';
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
}

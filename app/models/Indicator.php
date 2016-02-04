<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Indicator
 *
 * @author dhutchful
 */

use Venturecraft\Revisionable\Revisionable;


class Indicator extends Revisionable { 

	protected $table = 'cch_indicators';

    protected $revisionEnabled = true;
    protected $revisionCleanup = true; //Remove old revisions (works only when used with $historyLimit)
    protected $historyLimit = 500;
    protected $revisionCreationsEnabled = true;
    protected $revisionNullString = 'nothing';
    protected $revisionUnknownString = 'unknown';

    public function identifiableName()
    {
        return $this->type.'-'.$this->care;
    }

       
    public function stats()
    {
        return $this->hasMany('IndicatorTracker');
    }

}


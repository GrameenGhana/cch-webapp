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
class Indicator extends Eloquent { 

	protected $table = 'cch_indicators';
       
    public function stats()
    {
        return $this->hasMany('IndicatorTracker');
    }

}


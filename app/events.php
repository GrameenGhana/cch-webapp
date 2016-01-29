<?php
/*
|--------------------------------------------------------------------------
| Application Events
|--------------------------------------------------------------------------
|
| Here is where you can register all of the events for an application.
*/

Event::listen('eloquent.saved: ZonePopulation', function($popinfo)
{
    Log::info("Zone Saved " . $popinfo->population);
    IndicatorTracker::updateTrackerTarget($popinfo);
});

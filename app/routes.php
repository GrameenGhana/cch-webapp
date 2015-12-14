<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
*/

Route::get('/distusers',           array('uses' => 'UserController@districtAdminList'));
Route::get('/getUsersInDistricts', array('uses' => 'DeviceController@getDistrictUsers'));
Route::get('/getDistricts',        array('uses' => 'DistrictController@getDistricts'));

/**** Dashboard routes ***/
Route::get('/indicators',            array('uses'=>  'DashboardController@showIndicators'));
Route::post('indicatorsdata',        array('uses' => 'DashboardController@indicatorsData'));
Route::post('indicatorsdatabycare',  array('uses' => 'DashboardController@indicatorsDataByCare'));
Route::get('/stayingwell',           array('uses' => 'DashboardController@showStayingWell'));
Route::get('swplansbyprofile',       array('uses' => 'DashboardController@swPlansByProfile'));
Route::post('swplansbyprofile',      array('uses' => 'DashboardController@swPlansByProfile'));
Route::get('moduleusagebytype',      array('uses' => 'DashboardController@moduleUsageByType'));
Route::post('moduleusagebytype',     array('uses' => 'DashboardController@moduleUsageByType'));
/**** / Dasboard routes ***/

/**** Target routes ***/
Route::post('/targets/actuals/{id}',                                     'IndicatorTrackerController@update');
Route::resource('/targets/actuals',                                      'IndicatorTrackerController');
Route::resource('/targets/population/districts',                         'DistrictPopulationController');

Route::get('/targets/population/subdistricts/bulkedit/{id}/{year}',      array('uses' => 'SubDistrictPopulationController@districtView'))->before('auth');
Route::post('/targets/population/subdistricts/bulkedit',                 array('uses' => 'SubDistrictPopulationController@updateAll'))->before('auth');
Route::resource('/targets/population/subdistricts',                      'SubDistrictPopulationController');

Route::get('/getZonePopulationData',                                     array('uses' => 'ZonePopulationController@index'));
Route::get('/targets/population/zones/bulkedit/{id}',                    array('uses' => 'ZonePopulationController@indexAll'))->before('auth');
Route::get('/targets/population/zones/bulkedit/district/{id}/{year}',    array('uses' => 'ZonePopulationController@districtView'))->before('auth');
Route::get('/targets/population/zones/bulkedit/subdistrict/{id}/{year}', array('uses' => 'ZonePopulationController@subDistrictView'))->before('auth');
Route::post('/targets/population/zones/bulkedit',                        array('uses' => 'ZonePopulationController@updateAll'));
Route::resource('targets/population/zones',                              'ZonePopulationController');
/**** / Target routes ***/

Route::get('/getFacilityZones', array('uses' => 'ZoneController@getByFacilityZones'));
Route::get('/getDistrictTotalPopulationZones', array('uses' => 'DistrictPopulationController@getDistrictTotalPopulationZones'));
Route::get('/getDistrictTotalPopulation', array('uses' => 'DistrictPopulationController@getDistrictTotalPopulation'));
Route::get('/getSubDistrictTotalPopulation', array('uses' => 'DistrictPopulationController@getSubDistrictTotalPopulation'));
Route::get('/getSubDistricts', array('uses' => 'SubDistrictController@getSubDistricts'));
Route::get('/getFacilities', array('uses' => 'FacilityController@getFacilities'));
Route::get('/getZones', array('uses' => 'ZoneController@getZones'));
Route::get('/districtadmin', array('uses' => 'UserController@indexDistrictAdmin'));
Route::get('/districtadmin/create', array('uses' => 'UserController@districtAdminCreate'));
Route::resource('users','UserController');
Route::resource('facilities','FacilityController'); 
Route::resource('facilitytypes','FacilityTypeController'); 
Route::resource('facilitytype','FacilityTypeController'); 
Route::resource('devices','DeviceController'); 
Route::resource('tracker','TrackerController'); 
Route::resource('districts','DistrictController'); 
Route::resource('subdistricts','SubDistrictController'); 
Route::resource('zones','ZoneController'); 
Route::resource('reports','ReportController'); 
//Route::get('/reports/{id}', array('uses' => 'ReportController@userReport'))->before('auth');

Route::get('/courses',function(){ $courses=CourseDetails::details(); return $courses; });
Route::pattern('id','[0-9]+');
Route::get('/districts/people/{id}', array('uses' => 'DistrictController@showPeople'))->before('auth');
Route::get('/', array('uses' => 'HomeController@showHome'))->before('auth');
Route::get('/facilities/calendar/{id}', array('uses' => 'FacilityController@showCalendar'))->before('auth');
Route::get('/facilities/districtcalendar/{id}/{district}', array('uses' => 'FacilityController@showDistrictCalendar'))->before('auth');
Route::get('/facilities/people/{id}', array('uses' => 'FacilityController@showPeople'))->before('auth');
Route::get('/users/calendar/{id}', array('uses' => 'UserController@showCalendar'))->before('auth');
Route::get('/users/courses/{id}', array('uses' => 'UserController@showCourses'))->before('auth');
Route::get('login', array('uses' => 'HomeController@showLogin'))->before('guest');
Route::post('login', array('uses' => 'HomeController@doLogin'));
Route::get('logout', array('uses' => 'HomeController@doLogout'))->before('auth');

//Apis
Route::group(array('prefix' => 'api/v1'), function()
{
    Route::resource('tracker','TrackerController'); 
    Route::resource('users','UserController');
    Route::resource('incharge','InChargeController');
    Route::get('getSupData/{id}', 'InChargeController@getSupData');
    Route::get('details/{id}', 'InChargeController@showdetail');
    Route::get('achievements/{id}','InChargeController@achievements');
});

Route::get('/getTargets', array('uses' => 'ApiController@getTargets'));
Route::get('/getNurses', array('uses' => 'ApiController@getAllNurses'));
Route::post('/pushFacilityTargets', array('uses' => 'ApiController@pushFacilityTargets'));
Route::get('/getFacilityTargets', array('uses' => 'ApiController@getFacilityTargets'));

Blade::extend(function($value) { return preg_replace('/\{\?(.+)\?\}/', '<?php ${1} ?>', $value); });

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
Route::get('/indicatorsdatabynurse/{id}',  array('uses' => 'DashboardController@indicatorsDataByNurse'));
Route::get('/indicatorsdatabyzone/{id}',  array('uses' => 'DashboardController@indicatorsDataByZone'));
Route::get('/stayingwell',           array('uses' => 'DashboardController@showStayingWell'));
Route::get('swplansbyprofile',       array('uses' => 'DashboardController@swPlansByProfile'));
Route::post('swplansbyprofile',      array('uses' => 'DashboardController@swPlansByProfile'));
Route::get('moduleusagebytype',      array('uses' => 'DashboardController@moduleUsageByType'));
Route::post('moduleusagebytype',     array('uses' => 'DashboardController@moduleUsageByType'));
/**** / Dasboard routes ***/

/**** Content routes ***/
Route::get('/content',                         array('uses'=>  'PocCmsController@home'));
Route::get('/content/poccms',                  array('uses'=>  'PocCmsController@home'));
Route::get('/content/poccms/onepage',          array('uses'=>  'PocCmsController@onePage'));
Route::get('/content/poccms/view',             array('uses'=>  'PocCmsController@view'));
Route::get('/content/poccms/add',              array('uses'=>  'PocCmsController@add'));
Route::get('/content/poccms/edit',             array('uses'=>  'PocCmsController@edit'));
Route::get('/content/poccms/editsection',      array('uses'=>  'PocCmsController@editSection'));
Route::get('/content/poccms/delete',           array('uses'=>  'PocCmsController@delete'));
Route::get('/content/poccms/deletesection',    array('uses'=>  'PocCmsController@deleteSection'));
Route::get('/content/poccms/forms',            array('uses'=>  'PocCmsController@forms'));
Route::get('/content/poccms/section',          array('uses'=>  'PocCmsController@section'));
Route::get('/content/poccms/upload',           array('uses'=>  'PocCmsController@upload'));
Route::get('/content/poccms/uploadFiles',      array('uses'=>  'PocCmsController@uploadFiles'));
Route::get('/content/poccms/alluploads',       array('uses'=>  'PocCmsController@allUploads'));
Route::post('/content/poccms/addsection',       array('uses'=>  'PocCmsController@addSection'));
Route::post('/content/poccms/editsectionvalue', array('uses'=>  'PocCmsController@editSectionValue'));
Route::post('/content/poccms/addpage',          array('uses'=>  'PocCmsController@addPage'));
Route::post('/content/poccms/addpagedetails',   array('uses'=>  'PocCmsController@addPageDetails'));
/**** / Content routes ***/

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

/*** System Setup **/
Route::resource('users','UserController');
Route::resource('facilities','FacilityController'); 
Route::resource('facilitytypes','FacilityTypeController'); 
Route::resource('facilitytype','FacilityTypeController'); 
Route::resource('devices','DeviceController'); 
Route::resource('tracker','TrackerController'); 
Route::resource('districts','DistrictController'); 
Route::resource('subdistricts','SubDistrictController'); 
Route::resource('zones','ZoneController'); 
Route::resource('reports','ReportController',['before'=>'auth']); 
/** end System Setup **/

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
    Route::get('dropdown/subdistricts', function() {
        $id = Input::get('id');
        $subs = District::find($id)->subdistricts;
        return $subs->lists('name','id');
    });
    
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

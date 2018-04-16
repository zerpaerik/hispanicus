<?php

	Route::group(['prefix' => '/v1', 'namespace' => 'Api', 'as' => 'api.auth'], function () {
		
		Route::post('register', 'Auth\AuthController@register');
		Route::post('login', 'Auth\AuthController@login');
		Route::post('checkemail', 'Auth\AuthController@checkEmail');
		Route::get('api_clients', 'Clients\ClientsController@index');
		Route::post('api_clients', 'Clients\ClientsController@store');
		
		Route::group(['middleware' => 'auth:api'], function () {
	    	Route::post('logout', 'Auth\AuthController@logout');
		});

	});

	Route::group(['prefix' => '/v1', 'middleware' => 'api'], function () {
    	Route::post('upload_verbos', 'Admin\VerbosController@upload');
    	Route::post('verbos_mass', 'Admin\VerbosController@storeVerboData');
    	Route::post('verbos/', 'Admin\VerbosController@storeRegular');
    	Route::post('dicts/', 'Admin\VerbosController@storeDict');
    	Route::post('verbos/regular_oc', 'Admin\VerbosController@storeRegularOrthChange');
    	Route::get('verbos/{tipo}', 'Admin\VerbosController@listVerbs');
    	Route::post('verbo/{id}', 'Admin\VerbosController@getVerb');

	});

Route::group(['prefix' => '/v1', 'middleware' => 'auth:api'], function() {

	Route::get('verbos/favs/', 'Admin\VerbosController@listFavs');
	Route::get('tutorial/{id}/', 'Admin\VerbosController@getTutorial');

	//CONFIG
	Route::get('favs/', 'Admin\ConfigRegionController@getFavs');
	Route::post('favs/', 'Admin\ConfigRegionController@setFavs');
	Route::get('lang/', 'Admin\ConfigRegionController@getLang');
	Route::post('lang/', 'Admin\ConfigRegionController@setLang');
	Route::get('region/', 'Admin\ConfigRegionController@getRegion');
	Route::post('region/', 'Admin\ConfigRegionController@setRegion');	
});
<?php

	Route::group(['prefix' => '/v1', 'namespace' => 'Api', 'as' => 'api.auth'], function () {
		
		Route::post('register', 'Auth\AuthController@register');
		Route::post('login', 'Auth\AuthController@login');
		Route::post('checkemail', 'Auth\AuthController@checkEmail');
		Route::get('api_clients', 'Clients\ClientsController@index');
		Route::post('api_clients', 'Clients\ClientsController@store');
		Route::post('consume_code', 'Clients\ClientsController@consumeCode');
		Route::get('checkuuid', 'Clients\ClientsController@check')->middleware('checkuuid');
		
		Route::group(['middleware' => 'auth:api'], function () {
	    	Route::post('logout', 'Auth\AuthController@logout');
	    	Route::post('sendmessage', 'Auth\AuthController@sendAMessage');
		});

	});

	Route::group(['prefix' => '/v1', 'middleware' => 'api'], function () {
    	Route::post('upload_verbos', 'Admin\VerbosController@upload');
    	Route::post('verbos_mass', 'Admin\VerbosController@storeVerboData');
    	Route::get('verbos/search/{verbo}', 'Admin\VerbosController@searchVerbo');
    	Route::post('verbos/', 'Admin\VerbosController@storeRegular');
    	Route::post('dicts/', 'Admin\VerbosController@storeDict');
    	Route::post('info/', 'Admin\VerbosController@storeInfo');
    	Route::get('info/{lang}/{tipo}', 'Admin\VerbosController@getInfo');
    	Route::post('verbos/regular_oc', 'Admin\VerbosController@storeRegularOrthChange');
    	Route::get('verbos/{tipo}/{lang}', 'Admin\VerbosController@listVerbs')->middleware('checkuuid');
    	Route::post('verbo/{id}', 'Admin\VerbosController@getVerb');
    	Route::post('free/verbo/{id}', 'Admin\VerbosController@getFreeVerb');
    	Route::post('delete', 'Admin\VerbosController@delRaices');

	});

Route::group(['prefix' => '/v1', 'middleware' => 'auth:api'], function() {

	Route::get('favoritos/', 'Admin\VerbosController@listFavs');
	Route::get('tutorial/{id}/', 'Admin\VerbosController@getTutorial');

	//CONFIG
	Route::get('favs/', 'Admin\ConfigRegionController@getFavs');
	Route::post('favs/', 'Admin\ConfigRegionController@setFavs');
	Route::get('lang/', 'Admin\ConfigRegionController@getLang');
	Route::post('lang/', 'Admin\ConfigRegionController@setLang');
	Route::get('region/', 'Admin\ConfigRegionController@getRegion');
	Route::post('region/', 'Admin\ConfigRegionController@setRegion');	
});
<?php

	Route::group(['prefix' => '/v1', 'namespace' => 'Api', 'as' => 'api.auth'], function () {
		
		Route::post('register', 'Auth\AuthController@register');
		Route::post('login', 'Auth\AuthController@login');
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
    	Route::post('verbos/regular_oc', 'Admin\VerbosController@storeRegularOrthChange');
    	Route::get('verbos', 'Admin\VerbosController@listVerbs');
    	Route::get('verbo/{id}', 'Admin\VerbosController@getVerb');
	});


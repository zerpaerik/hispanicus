<?php

Route::group(['prefix' => '/v1', 'namespace' => 'Api', 'as' => 'api.auth'], function () {
	
	Route::post('register', 'Auth\AuthController@register');
	Route::post('login', 'Auth\AuthController@login');
	
	Route::group(['middleware' => 'auth:api'], function () {
    	Route::post('logout', 'Auth\AuthController@logout');	
	});

});

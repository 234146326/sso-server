<?php

//登录界面
Route::get('/', 'LoginController@index');
Route::get('/logout', 'LoginController@logout');
Route::controllers([
	'login' => 'LoginController',
	'api' => 'ApiController',
]);

<?php
Route::group([
	'middleware' => 'auth',
	'as' => 'css.'
], function() {
	
	Route::get('css/create', 'CssController@create');
	Route::post('css/createcss', 'CssController@createcss');
});
//Route::get('css/welcome/{id}', 'CssController@index');
Route::get('css/{token}/{id}', 'CssController@make') ;
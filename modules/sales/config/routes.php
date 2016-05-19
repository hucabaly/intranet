<?php
Route::group([
	'middleware' => 'auth',
	'as' => 'css.'
], function() {
	
	Route::get('css/create', 'CssController@create');
	Route::post('css/savecss', 'CssController@savecss');
});
//Route::get('css/welcome/{id}', 'CssController@index');
Route::get('css/make/{token}/{id}', 'CssController@make') ;
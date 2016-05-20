<?php
Route::group([
	'middleware' => 'auth',
	'as' => 'css.'
], function() {
	
	Route::get('css/create', 'CssController@create');
	Route::get('css/update/{id}', 'CssController@update');
	Route::post('css/save', 'CssController@save');
	Route::get('css/preview/{token}/{id}', 'CssController@preview');
});

Route::get('css/make/{token}/{id}', 'CssController@make') ;
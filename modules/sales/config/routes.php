<?php
Route::group([
	'middleware' => 'auth',
	'as' => 'css.'
], function() {
	
	Route::get('css/create', 'CssController@create');
	Route::get('css/update/{id}', 'CssController@update');
	Route::post('css/save', 'CssController@save');
        Route::post('css/saveResult', 'CssController@saveResult');
	Route::get('css/preview/{token}/{id}', 'CssController@preview');
        Route::get('sales/css/list', 'CssController@grid');
        Route::get('css/view/{id}', 'CssController@view');
        Route::get('css/detail/{id}', 'CssController@detail');
});

Route::get('css/make/{token}/{id}', 'CssController@make') ;
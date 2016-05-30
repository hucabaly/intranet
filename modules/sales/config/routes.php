<?php
Route::group([
	'middleware' => 'auth',
	'as' => 'css.'
], function() {
	
	Route::get('css/create', 'CssController@create')->name('create');
	Route::get('css/update/{id}', 'CssController@update')->name('update');
	Route::post('css/save', 'CssController@save')->name('save');
        Route::post('css/saveResult', 'CssController@saveResult')->name('saveResult');
	Route::get('css/preview/{token}/{id}', 'CssController@preview')->name('saveResult');
        Route::get('sales/css/list', 'CssController@grid')->name('list');
        Route::get('css/view/{id}', 'CssController@view')->name('view');
        Route::get('css/detail/{id}', 'CssController@detail')->name('detail');
        
        Route::get('css/cancel', 'CssController@cancelMake')->name('cancel');
});
Route::get('css/success/{id}', 'CssController@success')->name('success');
Route::get('css/make/{token}/{id}', 'CssController@make')->name('make') ;
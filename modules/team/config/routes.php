<?php

Route::group([
    'prefix' => 'setting/team',
    'as' => 'setting.'
], function() {
    Route::get('index','SettingController@index')->name('index');
    Route::get('/','SettingController@index')->name('index');
    
    //setting team
    Route::group([
        'as' => 'team.',
    ], function() {
        Route::get('view/{id}','TeamController@view')->name('view')->where('id', '[0-9]+');
        Route::post('move','TeamController@move')->name('move');
        Route::post('save','TeamController@save')->name('save');
        Route::delete('delete','TeamController@delete')->name('delete');
        
        //setting position team
        Route::group([
            'prefix' => 'position',
            'as' => 'position.',
        ], function() {
            Route::get('view/{id}','TeamPositionController@view')->name('view')->where('id', '[0-9]+');
            Route::post('move','TeamPositionController@move')->name('move');
            Route::post('save','TeamPositionController@save')->name('save');
            Route::delete('delete','TeamPositionController@delete')->name('delete');
        });
    });
});

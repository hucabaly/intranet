<?php

Route::group([
    'prefix' => 'setting/team',
    'as' => 'setting.'
], function() {
    Route::get('index','SettingController@index')->name('index');
    Route::get('/','SettingController@index')->name('index');
    
    //setting team
    Route::get('view/{id}','TeamController@view')->name('team.view')->where('id', '[0-9]+');
    Route::group([
        'prefix' => 'team',
        'as' => 'team.',
    ], function() {
        Route::post('move','TeamController@move')->name('move');
        Route::post('save','TeamController@save')->name('save');
        Route::delete('delete','TeamController@delete')->name('delete');
    });
    
    //setting position team
});

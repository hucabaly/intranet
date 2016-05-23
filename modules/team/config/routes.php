<?php

Route::group([
    'prefix' => 'team/setting',
    'as' => 'setting.'
], function() {
    Route::get('index','SettingController@index')->name('index');
    Route::get('/','SettingController@index')->name('index');
    Route::get('view/{id}','SettingController@viewTeam')->name('viewTeam')->where('id', '[0-9]+');
    Route::post('moveTeam','SettingController@moveTeam')->name('moveTeam');
    Route::post('saveTeam','SettingController@saveTeam')->name('saveTeam');
    Route::get('delete','SettingController@delete')->name('delete');
});

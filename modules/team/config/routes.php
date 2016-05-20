<?php

Route::group([
    'prefix' => 'team/setting',
    'as' => 'setting.'
], function() {
    Route::get('index','SettingController@index')->name('index');
//    Route::get('create','SettingController@create')->name('create');
    Route::post('saveTeam','SettingController@saveTeam')->name('saveTeam');
    Route::get('edit','SettingController@edit')->name('edit');
    Route::get('delete','SettingController@delete')->name('delete');
});

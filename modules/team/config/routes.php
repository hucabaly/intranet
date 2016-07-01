<?php

Route::group([
    'prefix' => 'setting/team',
    'as' => 'setting.team.'
], function() {
    Route::get('index','SettingController@index')->name('index');
    Route::get('/','SettingController@index')->name('index');
    
    //setting team
    Route::get('view/{id}','TeamController@view')->name('view')->where('id', '[0-9]+');
    Route::post('move','TeamController@move')->name('move');
    Route::post('save','TeamController@save')->name('save');
    Route::delete('delete','TeamController@delete')->name('delete');

    //setting position team
    Route::group([
        'prefix' => 'position',
        'as' => 'position.',
    ], function() {
        Route::get('view/{id}','PositionController@view')->name('view')->where('id', '[0-9]+');
        Route::post('move','PositionController@move')->name('move');
        Route::post('save','PositionController@save')->name('save');
        Route::delete('delete','PositionController@delete')->name('delete');
    });

    //setting rule
    Route::group([
        'prefix' => 'rule',
        'as' => 'rule.',
    ], function() {
        Route::post('save','PermissionController@saveTeam')->name('save');
    });
});

//setting role
Route::group([
    'prefix' => 'setting/role',
    'as' => 'setting.role.'
], function() {
    Route::get('view/{id}','RoleController@view')->name('view')->where('id', '[0-9]+');
    Route::post('save','RoleController@save')->name('save');
    Route::delete('delete','RoleController@delete')->name('delete');
    Route::post('rule/save', 'PermissionController@saveRole')->name('rule.save');
});

//manage setting
Route::group([
    'prefix' => 'setting',
    'as' => 'setting.'
], function() {
    //setting acl action
    Route::group([
        'prefix' => 'acl',
        'as' => 'acl.'
    ], function() {
        Route::get('/','AclController@index')->name('index');
        Route::get('create','AclController@create')->name('create');
        Route::get('edit/{id}','AclController@edit')->name('edit')->where('id', '[0-9]+');
        Route::post('save','AclController@save')->name('save');
        Route::delete('delete','AclController@delete')->name('delete');
    });
});


//team 
Route::group([
    'prefix' => 'team',
    'as' => 'team.'
], function() {
    // member manage
    Route::group([
        'prefix' => 'member',
        'as' => 'member.'
    ], function() {
        Route::get('/','MemberController@index')->name('index');
        Route::get('create','MemberController@create')->name('create');
        Route::get('edit/{id}','MemberController@edit')->name('edit')->where('id', '[0-9]+');
        Route::post('save','MemberController@save')->name('save');
        Route::delete('leave','MemberController@leave')->name('leave');
    });
});

Route::get('profile','ProfileController@profile')->name('member.profile');
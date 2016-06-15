<?php

// Home page
Route::get('/', 'PagesController@home')->name('home');
Route::get('/auth/connect/{provider}', 'AuthController@login')->name('login');
Route::get('/auth/connected/{provider}', 'AuthController@callback')->name('login.callback');
Route::get('/logout', 'AuthController@logout')->name('logout');

// Change locale
Route::get('/set-locale/{locale}', 'LocaleController@change')->name('change-locale');

//error page
Route::get('404', 'ErrorController@noRoute')->name('no.route');
Route::get('errors', 'ErrorController@errors')->name('errors.system');

//grid filter action
Route::get('/grid/filter/request', 'GridFilterController@request')->name('grid.filter.request');
Route::get('/grid/filter/remove', 'GridFilterController@remove')->name('grid.filter.remove');

//manage setting
Route::group([
    'prefix' => 'setting',
    'as' => 'setting.',
    'middleware' => ['auth']
], function() {
    //setting menu action
    Route::group([
        'prefix' => 'menu',
        'as' => 'menu.'
    ], function() {
        //menu item
        Route::group([
            'prefix' => 'item',
            'as' => 'item.'
        ], function() {
            Route::get('/','MenuItemController@index')->name('index');
            Route::get('create','MenuItemController@create')->name('create');
            Route::get('edit/{id}','MenuItemController@edit')->name('edit')->where('id', '[0-9]+');
            Route::post('save','MenuItemController@save')->name('save');
        });
        
        //menu group
        Route::group([
            'prefix' => 'group',
            'as' => 'group.'
        ], function() {
            Route::get('/','MenuGroupController@index')->name('index');
            Route::get('create','MenuGroupController@create')->name('create');
            Route::get('edit/{id}','MenuGroupController@edit')->name('edit')->where('id', '[0-9]+');
            Route::post('save','MenuGroupController@save')->name('save');
            Route::post('delete','MenuGroupController@delete')->name('delete');
        });
    });
});
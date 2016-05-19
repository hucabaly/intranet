<?php

// Home page
Route::get('/', 'PagesController@home');
Route::get('/auth/connect/{provider}', 'AuthController@login');
Route::get('/auth/connected/{provider}', 'AuthController@callback');
Route::get('/logout', 'AuthController@logout');

// Change locale
Route::get('/set-locale/{locale}', 'LocaleController@change')->name('change-locale');

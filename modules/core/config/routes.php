<?php

// Home page
Route::get('/', 'PagesController@home')->name('home');
Route::get('/auth/connect/{provider}', 'AuthController@login')->name('login');
Route::get('/auth/connected/{provider}', 'AuthController@callback')->name('login.callback');
Route::get('/logout', 'AuthController@logout')->name('logout');

// Change locale
Route::get('/set-locale/{locale}', 'LocaleController@change')->name('change-locale');

//error page
Route::get('/errors', 'ErrorController@view')->name('errors');

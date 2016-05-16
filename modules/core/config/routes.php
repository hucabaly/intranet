<?php

// Home page
Route::get('/', 'PagesController@home');
Route::get('auth/connect/{provider}', 'AuthController@login');
Route::get('auth/connected/{provider}', 'AuthController@callback');
Route::get('auth/disconnect/{provider}', 'AuthController@logout');

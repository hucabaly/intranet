<?php
Route::group([
	'middleware' => 'auth',
	'as' => 'css.'
], function() {
	Route::get('css/create', 'CssController@create')->name('create');
	Route::get('css/update/{id}', 'CssController@update')->name('update');
	Route::post('css/save', 'CssController@save')->name('save');
        Route::get('css/preview/{token}/{id}', 'CssController@preview')->name('preview');
        Route::get('sales/css/list', 'CssController@grid')->name('list');
        Route::get('css/view/{id}', 'CssController@view')->name('view');
        Route::get('css/detail/{id}', 'CssController@detail')->name('detail');
        Route::get('css/cancel', 'CssController@cancelMake')->name('cancel');
        Route::get('css/analyze', 'CssController@analyze')->name('analyze');
        Route::post('css/filter_analyze', 'CssController@filterAnalyze')->name('filterAnalyze');
        Route::post('css/apply_analyze', 'CssController@applyAnalyze')->name('applyAnalyze');
        Route::post('css/show_analyze_list_project/{criteriaIds}/{teamIds}/{projectTypeIds}/{startDate}/{endDate}/{criteriaType}/{curpage}/{orderBy}/{ariaType}', 'CssController@showAnalyzeListProject')->name('showAnalyzeListProject');
        Route::post('css/get_list_less_three_star/{cssresultids}/{curpage}/{orderby}/{ariatype}', 'CssController@getListLessThreeStar')->name('getListLessThreeStar');
        Route::post('css/get_proposes/{cssresultids}/{curpage}/{orderby}/{ariatype}', 'CssController@getProposes')->name('getProposes');
        Route::post('css/get_list_less_three_star_question/{questionid}/{cssresultids}/{curpage}/{orderby}/{ariatype}', 'CssController@getListLessThreeStarByQuestion')->name('getListLessThreeStarByQuestion');
        Route::post('css/get_proposes_question/{questionid}/{cssresultids}/{curpage}/{orderby}/{ariatype}', 'CssController@getProposesByQuestion')->name('getProposesByQuestion');
        Route::get('css/export_excel/{cssresultid}', 'CssController@exportExcel')->name('exportExcel');
});
Route::get('css/success', 'CssController@success')->name('success');
Route::get('css/welcome/{token}/{id}', 'CssController@welcome')->name('welcome') ;
Route::post('css/welcome/{token}/{id}', 'CssController@welcome')->name('welcome') ;
Route::get('css/make/{token}/{id}', 'CssController@make')->name('make') ;
Route::post('css/saveResult', 'CssController@saveResult')->name('saveResult');


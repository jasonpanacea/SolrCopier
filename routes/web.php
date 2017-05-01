<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::post('getIndexList', 'SolrCopierController@getIndexList');
Route::post('getFieldList', 'SolrCopierController@getFieldList');
Route::get('copyPage', 'SolrCopierController@copyPage');
Route::post('startSyncJob', 'SolrCopierController@startSyncJob');
Route::get('taskList', 'SolrCopierController@taskList');
Route::get('jobList', 'SolrCopierController@jobList');
Route::get('jobProgress', 'SolrCopierController@jobProgress');
Route::get('getJobListByTaskID', 'SolrCopierController@getJobListByTaskID');
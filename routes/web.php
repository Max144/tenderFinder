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

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/', 'HomeController@index')->name('newTenders');
    Route::get('/all', 'HomeController@all')->name('allTenders');
});

Route::get('/calc', function (){
    return view('calc');
})->name('calc');

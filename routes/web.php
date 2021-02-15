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

use App\Http\Controllers\Admin\MaterialsController;

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/', 'HomeController@index')->name('newTenders');
    Route::get('/all', 'HomeController@all')->name('allTenders');
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('materials', 'Admin\MaterialsController')->only([
            'index', 'show', 'create', 'store', 'edit', 'update'
        ]);
        Route::post('materials/{id}/destroy', 'Admin\MaterialsController@destroy')->name('materials.destroy');
        Route::post('materials/{material}/update-thicknesses', 'Admin\MaterialsController@updateThicknesses')
            ->name('materials.update-thicknesses');
    });
});

Route::get('/calc', 'CalcController@calc');

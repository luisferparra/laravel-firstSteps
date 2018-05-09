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

Route::get('/', 'Front\Welcome@welcomePage');



Route::get('/form','FormsController@index');
Route::post('/form','FormsController@insert');
Route::get('/form/thanks','FormsController@thanks')->name('FormsThanks');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/**
 * Rutas ADMIN
 */
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/registers','Admin\AdminRegisters@index')->name('AdminRegistersList');
    Route::get('/admin/registers/new','Admin\AdminRegisters@newRegister')->name('AdminRegistersNew');
    Route::post('/admin/registers/new','Admin\AdminRegisters@insertRegister');  
    Route::get('/admin/registers/edit/{register}','Admin\AdminRegisters@editRegister')
            ->where('register','[0-9]+')->name('AdminRegistersEdit');
    Route::post('/admin/registers/edit/{register}','Admin\AdminRegisters@updateRegister')
            ->where('register','[0-9]+')->name('AdminRegistersEditPost');

});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
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
Route::group(['middleware'    => 'auth'],function(){
    Route::get('/home',[AdminController::class, 'index']);
    Route::get('/',[AdminController::class, 'index']);
    Route::get('/App',[AdminController::class, 'index']);
    Route::get('/App/hapus',[AdminController::class, 'hapus']);
    Route::get('/App/ubah',[AdminController::class, 'ubah']);
    Route::post('/App',[AdminController::class, 'simpan']);
    Route::post('/App/hapus_multiple',[AdminController::class, 'hapus_multiple']);
    Route::post('/App/update',[AdminController::class, 'simpan_ubah']);
});

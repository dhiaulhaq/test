<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\PengaduanController;
use Illuminate\Support\Facades\Route;

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
    return view('home');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticated']);
});
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/dashboard', [PengaduanController::class, 'dashboard']);
Route::get('/pengaduan', [PengaduanController::class, 'showCMS']);
Route::delete('/pengaduan/{id}', [PengaduanController::class, 'destroy'])->name('pengaduan.destroy');
Route::post('/pengaduan/{id}/tanggapan', [PengaduanController::class, 'reply'])->name('pengaduan.tanggapan');
Route::get('/tanggapan', [PengaduanController::class, 'showTanggapan']);
Route::delete('/tanggapan/{id}', [PengaduanController::class, 'tanggapanDestroy'])->name('tanggapan.destroy');
Route::get('/form', [PengaduanController::class, 'index']);
Route::post('/form/store', 'App\Http\Controllers\PengaduanController@store')->name('form.store');
Route::get('/histori', [PengaduanController::class, 'show']);
Route::get('/galeri', [GalleryController::class, 'index']);
Route::get('/galericms', [GalleryController::class, 'show']);
Route::post('/galericms/store', 'App\Http\Controllers\GalleryController@store')->name('galericms.store');
Route::put('/galericms/update/{id}', 'App\Http\Controllers\GalleryController@update')->name('galericms.update');
Route::delete('/galericms/delete/{id}', 'App\Http\Controllers\GalleryController@destroy')->name('galericms.destroy');


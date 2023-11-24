<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PresensiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::middleware(['guest:karyawan'])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');
});

Route::post('/proseslogin', [AuthController::class, 'proseslogin']);


Route::middleware(['auth:karyawan'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/proseslogout', [AuthController::class, 'proseslogout']);

    //presensi
    Route::get('/create', [PresensiController::class, 'create']);
    Route::post('/presensi/store', [PresensiController::class, 'store']);

    // edit profile
    Route::get('/editProfile', [PresensiController::class,'editProfile']);
    Route::post('/presensi/{nik}/updateProfile', [PresensiController::class, 'updateProfile']);

    // histori
    Route::get('/histori', [PresensiController::class, 'histori']);
    Route::post('/getHistori', [PresensiController::class, 'getHistori']);

    // izin
    Route::get('/izin', [PresensiController::class, 'izin']);
    Route::get('/buatIzin', [PresensiController::class, 'buatIzin']);
    Route::post('/storeIzin', [PresensiController::class, 'storeIzin']);
});
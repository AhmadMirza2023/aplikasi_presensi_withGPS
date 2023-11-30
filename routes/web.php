<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\KonfigurasiController;

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
    Route::get('/panel2', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/prosesLogin', [AuthController::class, 'prosesLogin']);
});

Route::middleware(['auth:karyawan'])->group(function () {
    Route::get('/panel2/dashboard', [DashboardController::class, 'index']);
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

Route::middleware(['guest:user'])->group(function () {
    Route::get('/panel1', function () {
        return view('auth.loginAdmin');
    })->name('loginAdmin');
    Route::post('/prosesloginadmin', [AuthController::class, 'prosesloginadmin']);
});

Route::middleware(['auth:user'])->group(function () {
    Route::get('/panel1/dashboardAdmin', [DashboardController::class, 'dashboardAdmin']);
    Route::get('/proseslogoutadmin', [AuthController::class, 'proseslogoutadmin']);

    // karyawan
    Route::get('/karyawan', [KaryawanController::class, 'index']);
    Route::post('/karyawan/store', [KaryawanController::class, 'store']);
    Route::post('/karyawan/edit', [KaryawanController::class, 'edit']);
    Route::post('/karyawan/{nik}/update', [KaryawanController::class, 'update']);
    Route::post('/karyawan/{nik}/delete', [KaryawanController::class, 'delete']);

    // departemen
    Route::get('/departemen', [DepartemenController::class, 'index']);
    Route::post('/departemen/store', [DepartemenController::class, 'store']);
    Route::post('/departemen/edit', [DepartemenController::class, 'edit']);
    Route::post('/departemen/{kode_dept}/update', [DepartemenController::class, 'update']);
    Route::post('/departemen/{kode_dept}/delete', [DepartemenController::class, 'delete']);

    // presensi
    Route::get('/presensi/monitoring', [PresensiController::class, 'monitoring']);
    Route::post('/getpresensi', [PresensiController::class, 'getpresensi']);
    Route::post('/tampilkanpeta', [PresensiController::class, 'tampilkanpeta']);
    Route::get('/presensi/laporan', [PresensiController::class, 'laporan']);
    Route::post('/presensi/cetaklaporan', [PresensiController::class, 'cetaklaporan']);
    Route::get('/presensi/rekap', [PresensiController::class, 'rekap']);
    Route::post('/presensi/cetakrekap', [PresensiController::class, 'cetakrekap']);
    Route::get('/presensi/izinsakit', [PresensiController::class, 'izinsakit']);
    Route::post('/presensi/approveizinsakit', [PresensiController::class, 'approveizinsakit']);
    Route::get('/presensi/{id}/batalkanizinsakit', [PresensiController::class, 'batalkanizinsakit']);

    // konfigurasi
    Route::get('/konfigurasi/lokasikantor', [KonfigurasiController::class, 'lokasikantor']);
    Route::post('/konfigurasi/updatelokasikantor', [KonfigurasiController::class, 'updatelokasikantor']);
});
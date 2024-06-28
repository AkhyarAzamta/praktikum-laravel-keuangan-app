<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/kategori', [HomeController::class, 'kategori']);
Route::get('/kategori/tambah', [HomeController::class, 'kategori_tambah']);
Route::post('/kategori/aksi',[HomeController::class,'kategori_aksi']);
Route::get('/kategori/edit/{id}', [HomeController::class, 'kategori_edit']);
Route::put('/kategori/update/{id}', [HomeController::class, 'kategori_update']);
Route::get('/kategori/hapus/{id}', [HomeController::class, 'kategori_hapus']);
Route::get('/transaksi', [HomeController::class, 'transaksi']);
Route::get('/transaksi/tambah', [HomeController::class, 'transaksi_tambah']);
Route::post('/transaksi/aksi', [HomeController::class, 'transaksi_aksi']);
Route::get('/transaksi/edit/{id}', [HomeController::class, 'transaksi_edit']);
Route::put('/transaksi/update/{id}', [HomeController::class, 'transaksi_update']);
Route::get('/transaksi/hapus/{id}', [HomeController::class, 'transaksi_hapus']);
Route::get('/transaksi/cari',[HomeController::class,'transaksi_cari']);
Route::get('/laporan', [HomeController::class, 'laporan']);
Route::get('/laporan/hasil', [HomeController::class, 'laporan_hasil']);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

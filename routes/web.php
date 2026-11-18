<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriSpesiesController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\PenelitiController;
use App\Http\Controllers\RekamanController;
use App\Http\Controllers\SpesiesController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Kategori spesies (CRUD master kategori)
Route::resource('kategori', KategoriSpesiesController::class)
    ->parameters(['kategori' => 'kategori'])
    ->except(['show']);

// Spesies (data utama + multi-select habitat)
Route::resource('spesies', SpesiesController::class)
    ->parameters(['spesies' => 'spesies']);

// Lokasi perekaman
Route::resource('lokasi', LokasiController::class)
    ->parameters(['lokasi' => 'lokasi'])
    ->except(['show']);

// Peneliti
Route::resource('peneliti', PenelitiController::class)
    ->parameters(['peneliti' => 'peneliti'])
    ->except(['show']);

// Rekaman (data transaksi utama + detail analisis + multi-select peneliti)
Route::resource('rekaman', RekamanController::class)
    ->parameters(['rekaman' => 'rekaman']);

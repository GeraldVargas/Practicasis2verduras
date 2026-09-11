<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VerduraController;

Route::get('/', [VerduraController::class, 'index'])->name('verduras.index');
Route::post('/verduras', [VerduraController::class, 'store'])->name('verduras.store');
Route::get('/buscar', [VerduraController::class, 'buscar'])->name('verduras.buscar');
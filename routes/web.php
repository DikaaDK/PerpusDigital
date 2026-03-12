<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\BukuLikeController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('daftar-buku');
    }
    return redirect('/login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/daftar-buku', [BukuController::class, 'index'])->name('daftar-buku');
    Route::post('/daftar-buku', [BukuController::class, 'store']);
    Route::post('/buku/{buku}/ulasan', [BukuController::class, 'storeReview'])->name('buku.ulasan.store');
    Route::delete('/buku/{buku}', [BukuController::class, 'destroy'])->name('buku.destroy');
    Route::post('/buku/{buku}/like', [BukuLikeController::class, 'store'])->name('buku.like.store');
    Route::delete('/buku/{buku}/like', [BukuLikeController::class, 'destroy'])->name('buku.like.destroy');
    Route::get('/buku-disukai', [BukuController::class, 'liked'])->name('buku-disukai');
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::patch('/peminjaman/{peminjaman}/complete', [PeminjamanController::class, 'complete'])->name('peminjaman.complete');
    Route::get('/riwayat-peminjaman', [PeminjamanController::class, 'history'])->name('riwayat-peminjaman');
});

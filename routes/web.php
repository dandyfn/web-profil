<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// 1. Route untuk Halaman Utama Beranda
Route::get('/', function () {
    return view('welcome');
})->name('home');

// 2. Route Dinamis untuk Detail Pencapaian (Menggunakan slug card1, card2, dst)
Route::get('/achievement/{slug}', function ($slug) {

    // Mengambil seluruh isi data dari file config/achievements.php otomatis
    $achievements = config('achievements');

    // Pengaman: Jika slug (misal card99) tidak ada di file config, lempar ke error 404
    if (!array_key_exists($slug, $achievements)) {
        abort(404);
    }

    // Ambil data spesifik sesuai card yang di-klik
    $data = $achievements[$slug];

    // Kirim data tersebut ke file template detail
    return view('achievement-detail', compact('data'));
})->name('achievement.detail');

// 3. Route Dashboard & Profile Bawaan Laravel Breeze (Tetap Aman)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

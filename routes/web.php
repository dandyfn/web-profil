<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Blog;

// 1. Route untuk Halaman Utama Beranda (Mengambil 3 artikel terbaru saja)
// Route::get('/', function () {
//     $recentBlogs = Blog::latest()->take(3)->get();
//     return view('welcome', compact('recentBlogs'));
// })->name('home');

Route::get('/', function () {
    return 'Laravel is running!';
});

// 2. Route baru untuk Halaman Khusus Daftar Semua Blog
Route::get('/blog', function () {
    // Ambil seluruh artikel tanpa batasan limit, diurutkan dari yang terbaru
    $blogs = Blog::latest()->get();
    return view('blog', compact('blogs'));
})->name('blog.index');

// 3. Route Dinamis untuk Detail Pencapaian
Route::get('/achievement/{slug}', function ($slug) {
    $achievements = config('achievements');

    if (!array_key_exists($slug, $achievements)) {
        abort(404);
    }

    $data = $achievements[$slug];
    return view('achievement-detail', compact('data'));
})->name('achievement.detail');

// 4. Route Dinamis untuk Detail Isi Blog (Views Tracker)
Route::get('/blog/{slug}', function ($slug) {
    $blog = Blog::where('slug', $slug)->firstOrFail();
    $blog->increment('views'); // Counter otomatis pembaca

    return view('blog-detail', compact('blog'));
})->name('blog.detail');

// 5. Route Dashboard & Profile Bawaan Laravel Breeze
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Blog;

// 1. Halaman Utama (3 artikel terbaru)
Route::get('/', function () {
    $recentBlogs = Blog::latest()->take(3)->get();
    return view('welcome', compact('recentBlogs'));
})->name('home');

// 2. Halaman daftar semua blog
Route::get('/blog', function () {
    $blogs = Blog::latest()->get();
    return view('blog', compact('blogs'));
})->name('blog.index');

// 3. Detail pencapaian
Route::get('/achievement/{slug}', function ($slug) {
    $achievements = config('achievements');

    if (!array_key_exists($slug, $achievements)) {
        abort(404);
    }

    $data = $achievements[$slug];
    return view('achievement-detail', compact('data'));
})->name('achievement.detail');

// 4. Detail isi blog (views tracker)
Route::get('/blog/{slug}', function ($slug) {
    $blog = Blog::where('slug', $slug)->firstOrFail();
    $blog->increment('views');

    return view('blog-detail', compact('blog'));
})->name('blog.detail');

// 5. Dashboard & Profile bawaan Laravel Breeze
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/blog/{id}/comment', [ProfileController::class, 'storeComment'])->name('blog.comment.store');
Route::put('/comment/{id}', [ProfileController::class, 'updateComment'])->name('blog.comment.update');
Route::delete('/comment/{id}', [ProfileController::class, 'destroyComment'])->name('blog.comment.destroy');

require __DIR__.'/auth.php';

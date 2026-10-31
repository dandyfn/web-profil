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

// Chat pengunjung
Route::prefix('chat')->middleware('throttle:60,1')->group(function () {
    Route::post('/start', [\App\Http\Controllers\ChatController::class, 'start']);
    Route::get('/messages', [\App\Http\Controllers\ChatController::class, 'messages']);
    Route::post('/send', [\App\Http\Controllers\ChatController::class, 'send']);
});

// Sitemap otomatis untuk Google
Route::get('/sitemap.xml', function () {
    $blogs = Blog::select('slug', 'updated_at')->latest('updated_at')->get();

    $urls = [];

    $urls[] = [
        'loc' => url('/'),
        'lastmod' => optional($blogs->first())->updated_at ?? now(),
        'priority' => '1.0',
    ];

    $urls[] = [
        'loc' => route('blog.index'),
        'lastmod' => optional($blogs->first())->updated_at ?? now(),
        'priority' => '0.9',
    ];

    foreach ($blogs as $blog) {
        $urls[] = [
            'loc' => route('blog.detail', ['slug' => $blog->slug]),
            'lastmod' => $blog->updated_at,
            'priority' => '0.8',
        ];
    }

    foreach (array_keys(config('achievements') ?? []) as $slug) {
        $urls[] = [
            'loc' => route('achievement.detail', ['slug' => $slug]),
            'lastmod' => null,
            'priority' => '0.6',
        ];
    }

    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

    foreach ($urls as $item) {
        $xml .= "  <url>\n";
        $xml .= '    <loc>' . e($item['loc']) . "</loc>\n";
        if ($item['lastmod']) {
            $xml .= '    <lastmod>' . $item['lastmod']->toAtomString() . "</lastmod>\n";
        }
        $xml .= '    <priority>' . $item['priority'] . "</priority>\n";
        $xml .= "  </url>\n";
    }

    $xml .= '</urlset>';

    return response($xml, 200)->header('Content-Type', 'application/xml');
});

require __DIR__.'/auth.php';

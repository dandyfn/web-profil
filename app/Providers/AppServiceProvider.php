<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Di Render (bukan lokal), semua request sebenarnya lewat HTTPS.
        // Ini membuat Laravel mengenalinya, sehingga tanda tangan URL upload cocok.
        if (! app()->isLocal()) {
            URL::forceScheme('https');
            request()->server->set('HTTPS', 'on');
        }
    }
}

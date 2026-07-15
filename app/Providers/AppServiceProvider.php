<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract; // Import Contract bawaan Filament
use App\Http\Responses\LogoutResponse; // Import Response kustom kita

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // 🚀 BINDING LOGOUT: Memaksa Filament v3 melakukan redirect ke /blog setiap kali logout sukses!
        $this->app->bind(LogoutResponseContract::class, LogoutResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        if (config('app.env') === 'production' || isset($_SERVER['HTTPS'])) {
            URL::forceScheme('https');
        }
    }
}

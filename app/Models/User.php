<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// TAMBAHKAN LINE INI DI ATAS:
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

// TAMBAHKAN "implements FilamentUser" setelah nama class
class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // TAMBAHKAN METHOD BARU INI DI PALING BAWAH CLASS:
    public function canAccessPanel(Panel $panel): bool
    {
        // Hanya izinkan email kamu yang bisa membuka halaman admin
        return $this->email === 'dandy.alfarisi18@gmail.com';
    }
}

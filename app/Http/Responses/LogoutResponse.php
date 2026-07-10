<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LogoutResponse as Responsable;
use Illuminate\Http\RedirectResponse;

class LogoutResponse implements Responsable
{
    /**
     * 🚀 Mengalihkan rute default logout Filament menuju halaman blog publik
     */
    public function toResponse($request): RedirectResponse
    {
        return redirect()->to('/blog');
    }
}

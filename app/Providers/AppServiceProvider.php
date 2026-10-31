<?php

namespace App\Providers;

use App\Models\Achievement;
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
        // Di Render (bukan lokal), semua request lewat HTTPS.
        if (! app()->isLocal()) {
            URL::forceScheme('https');
            request()->server->set('HTTPS', 'on');
        }

        // Ambil data achievement dari database dan pasang ke config('achievements').
        // Jika database gagal dibaca, data lama di config/achievements.php dipakai.
        try {
            $data = [];

            foreach (Achievement::orderBy('sort_order')->orderBy('id')->get() as $item) {
                $attachments = [];

                foreach (($item->attachments ?? []) as $path) {
                    $attachments[] = ['type' => 'image', 'url' => Achievement::fileUrl($path)];
                }

                $data[$item->slug] = [
                    'title' => $item->title,
                    'category' => $item->category,
                    'date' => $item->issued_label,
                    'badge' => $item->badge,
                    'credential_url' => $item->credential_url,
                    'desc_1' => $item->description,
                    'skills' => $item->skills ?? [],
                    'quote' => $item->quote,
                    'attachments' => $attachments,
                ];
            }

            if (! empty($data)) {
                config(['achievements' => $data]);
            }
        } catch (\Throwable $e) {
            // Abaikan, pakai config bawaan.
        }
    }
}

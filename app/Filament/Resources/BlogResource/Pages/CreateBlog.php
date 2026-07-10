<?php

namespace App\Filament\Resources\BlogResource\Pages;

use App\Filament\Resources\BlogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBlog extends CreateRecord
{
    // Mengikat halaman ini dengan BlogResource utama
    protected static string $resource = BlogResource::class;

    /**
     * ❌ MELENYAPKAN TOMBOL "CREATE & CREATE ANOTHER"
     * Menggunakan properti statis bawaan Filament v3 untuk langsung menyembunyikan tombol tersebut.
     */
    protected static bool $canCreateAnother = false;

    /**
     * 🚀 PENGALIHAN SETELAH SUKSES CREATE
     * Setelah kamu menekan tombol "CREATE" dan berhasil, sistem akan langsung
     * mengalihkanmu pulang ke halaman daftar blog publik (/blog).
     */
    protected function getRedirectUrl(): string
    {
        return route('blog.index');
    }

    /**
     * 🚀 PENGALIHAN TOMBOL "CANCEL"
     * Mengarahkan tombol CANCEL di halaman tambah blog agar tidak 404,
     * melainkan langsung pulang ke halaman daftar blog publik (/blog).
     */
    protected function getCancelRedirectUrl(): string
    {
        return route('blog.index');
    }
}

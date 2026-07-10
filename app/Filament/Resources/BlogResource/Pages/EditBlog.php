<?php

namespace App\Filament\Resources\BlogResource\Pages;

use App\Filament\Resources\BlogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBlog extends EditRecord
{
    // Mengikat halaman ini dengan BlogResource utama
    protected static string $resource = BlogResource::class;

    /**
     * 🚀 PENGALIHAN SETELAH SUKSES EDIT (TOMBOL SAVE)
     * Setelah kamu menekan tombol "SAVE" dan berhasil memperbarui artikel,
     * sistem akan langsung mengalihkanmu pulang ke halaman daftar blog publik (/blog).
     */
    protected function getRedirectUrl(): string
    {
        return route('blog.index');
    }

    /**
     * 🚀 PENGALIHAN TOMBOL "CANCEL"
     * Memaksa tombol CANCEL di halaman edit blog agar tidak 404,
     * melainkan langsung pulang dengan selamat ke halaman daftar blog publik (/blog).
     */
    protected function getCancelRedirectUrl(): string
    {
        return route('blog.index');
    }

    /**
     * 🛠️ TOMBOL AKSES KEAMANAN (TOMBOL HAPUS / DELETE)
     * Mengatur tombol hapus bawaan Filament agar ketika kamu menghapus artikel ini,
     * sistem secara instan mengarahkan kembali navigasimu ke halaman daftar blog (/blog).
     *
     * NOTE: Filament v3 mewajibkan penggunaan method "successRedirectUrl" setelah aksi hapus sukses!
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->successRedirectUrl(route('blog.index')), // Pengalihan setelah sukses terhapus
        ];
    }
}

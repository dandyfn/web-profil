<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'description',
        'content',
        'image',
        'video_url',
        'source_link',
        'views',
        'author',
    ];

    /**
     * Nilai "image" selalu keluar sebagai URL penuh.
     * - Jika isinya sudah URL (Postimages dll), dipakai apa adanya.
     * - Jika isinya path file, diubah jadi URL Supabase Storage.
     * Dengan ini semua halaman (home, blog, detail) otomatis benar.
     */
    public function getImageAttribute($value)
    {
        if (! $value) {
            return null;
        }

        if (Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }

            return rtrim(config('filesystems.disks.s3.url'), '/') . '/' . ltrim($value, '/');return Storage::disk('s3')->url($value);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id')->latest();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Achievement extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'category',
        'issued_label',
        'badge',
        'credential_url',
        'description',
        'skills',
        'quote',
        'attachments',
        'sort_order',
    ];

    protected $casts = [
        'skills' => 'array',
        'attachments' => 'array',
    ];

    /**
     * Ubah path file menjadi URL yang bisa dibuka browser.
     * - URL penuh dipakai apa adanya
     * - "images/..." berarti file lama di folder public
     * - selain itu berarti file di Supabase Storage
     */
    public static function fileUrl(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        if (Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }

        if (Str::startsWith($value, 'images/')) {
            return asset(str_replace(' ', '%20', $value));
        }

        return rtrim(config('filesystems.disks.s3.url'), '/') . '/' . ltrim($value, '/');
    }

    public function getBadgeAttribute($value)
    {
        return self::fileUrl($value);
    }
}
